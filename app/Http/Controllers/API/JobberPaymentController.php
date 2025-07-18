<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\JobberInvoice;
use App\Models\JobberOAuthToken;

class JobberPaymentController extends Controller
{
    private $baseUrl = 'https://api.getjobber.com/api/graphql';
    private $apiVersion = '2023-08-18'; // Updated to supported version

    public function __construct()
    {
        // Use the latest supported version based on Jobber documentation
        $this->apiVersion = config('services.jobber.api_version', '2023-08-18');
    }

    /**
     * Step 1: Create a job in Jobber
     */
    public function createJob(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string',
            'client_email' => 'required|email',
            'client_phone' => 'nullable|string',
            'service_address' => 'required|string',
            'job_title' => 'required|string',
            'job_description' => 'nullable|string',
        ]);

        $user = auth()->user();

        $jobberToken = JobberOAuthToken::where('user_id', $user->id)->first();

        if (!$jobberToken) {
            return response()->json(['error' => 'No Jobber token found for this user'], 401);
        }

        // First, create or get client
        $clientId = $this->createOrGetClient($jobberToken->access_token, [
            'name' => $request->client_name,
            'email' => $request->client_email,
            'phone' => $request->client_phone,
        ]);

        if (!$clientId) {
            return response()->json(['error' => 'Failed to create client'], 400);
        }

        // Create job
        $jobMutation = [
            'query' => 'mutation CreateJob($input: JobCreateInput!) {
                jobCreate(input: $input) {
                    job {
                        id
                        jobNumber
                        title
                        status
                        client {
                            id
                            name
                        }
                    }
                    userErrors {
                        field
                        message
                    }
                }
            }',
            'variables' => [
                'input' => [
                    'clientId' => $clientId,
                    'title' => $request->job_title,
                    'description' => $request->job_description,
                ]
            ]
        ];

        $response = $this->makeJobberRequest($jobberToken->access_token, $jobMutation);

        if (isset($response['errors'])) {
            return response()->json(['error' => 'Failed to create job', 'details' => $response['errors']], 400);
        }

        $job = $response['data']['jobCreate']['job'];
        
        return response()->json([
            'success' => true,
            'job' => $job,
            'message' => 'Job created successfully'
        ]);
    }

    /**
     * Step 2: Create invoice for the job
     */
    public function createInvoice(Request $request)
    {
        $request->validate([
            'job_id' => 'required|string',
            'line_items' => 'required|array',
            'line_items.*.name' => 'required|string',
            'line_items.*.quantity' => 'required|numeric|min:1',
            'line_items.*.unit_price' => 'required|numeric|min:0',
            'line_items.*.description' => 'nullable|string',
        ]);

        $user = auth()->user();

        $jobberToken = JobberOAuthToken::where('user_id', $user->id)->first();

        if (!$jobberToken) {
            return response()->json(['error' => 'No Jobber token found for this user'], 401);
        }

        $jobId = $request->job_id;

        // Get job details first
        $job = $this->getJobDetails($jobberToken->access_token, $jobId);
        if (!$job) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        // Prepare line items (convert prices to cents)
        $lineItems = collect($request->line_items)->map(function ($item) {
            return [
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'unitPrice' => intval($item['unit_price'] * 100), // Convert to cents
                'description' => $item['description'] ?? '',
            ];
        })->toArray();

        // Create invoice
        $invoiceMutation = [
            'query' => 'mutation CreateInvoice($input: InvoiceCreateInput!) {
                invoiceCreate(input: $input) {
                    invoice {
                        id
                        invoiceNumber
                        subject
                        total
                        status
                        publicUrl
                        paymentUrl
                    }
                    userErrors {
                        field
                        message
                    }
                }
            }',
            'variables' => [
                'input' => [
                    'jobId' => $jobId,
                    'subject' => 'Invoice for ' . $job['title'],
                    'lineItems' => $lineItems,
                ]
            ]
        ];

        $response = $this->makeJobberRequest($jobberToken->access_token, $invoiceMutation);

        if (isset($response['errors']) || !empty($response['data']['invoiceCreate']['userErrors'])) {
            return response()->json([
                'error' => 'Failed to create invoice',
                'details' => $response['errors'] ?? $response['data']['invoiceCreate']['userErrors']
            ], 400);
        }

        $invoice = $response['data']['invoiceCreate']['invoice'];

        // Store invoice in your database
        $this->storeInvoiceLocally($invoice, $request->user()->id, $jobId);

        return response()->json([
            'success' => true,
            'invoice' => $invoice,
            'payment_url' => $invoice['paymentUrl'],
            'message' => 'Invoice created successfully'
        ]);
    }

    /**
     * Step 3: Send invoice to client
     */
    public function sendInvoice(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|string',
        ]);

        $user = auth()->user();

        $jobberToken = JobberOAuthToken::where('user_id', $user->id)->first();

        if (!$jobberToken) {
            return response()->json(['error' => 'No Jobber token found for this user'], 401);
        }

        $sendMutation = [
            'query' => 'mutation SendInvoice($input: InvoiceSendInput!) {
                invoiceSend(input: $input) {
                    invoice {
                        id
                        status
                        sentAt
                        publicUrl
                        paymentUrl
                    }
                    userErrors {
                        field
                        message
                    }
                }
            }',
            'variables' => [
                'input' => [
                    'invoiceId' => $request->invoice_id
                ]
            ]
        ];

        $response = $this->makeJobberRequest($jobberToken->access_token, $sendMutation);

        if (isset($response['errors']) || !empty($response['data']['invoiceSend']['userErrors'])) {
            return response()->json([
                'error' => 'Failed to send invoice',
                'details' => $response['errors'] ?? $response['data']['invoiceSend']['userErrors']
            ], 400);
        }

        $invoice = $response['data']['invoiceSend']['invoice'];

        return response()->json([
            'success' => true,
            'invoice' => $invoice,
            'message' => 'Invoice sent successfully'
        ]);
    }

    /**
     * Step 4: Get invoice payment status
     */
    public function getInvoiceStatus(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|string',
        ]);

        $user = auth()->user();

        $jobberToken = JobberOAuthToken::where('user_id', $user->id)->first();

        if (!$jobberToken) {
            return response()->json(['error' => 'No Jobber token found for this user'], 401);
        }

        $query = [
            'query' => 'query GetInvoice($invoiceId: ID!) {
                invoice(id: $invoiceId) {
                    id
                    invoiceNumber
                    status
                    total
                    amountPaid
                    amountOwing
                    sentAt
                    paidAt
                    publicUrl
                    paymentUrl
                    payments {
                        nodes {
                            id
                            amount
                            paidAt
                            paymentMethod
                            status
                        }
                    }
                }
            }',
            'variables' => [
                'invoiceId' => $request->invoice_id
            ]
        ];

        $response = $this->makeJobberRequest($jobberToken->access_token, $query);

        if (isset($response['errors'])) {
            return response()->json(['error' => 'Failed to get invoice', 'details' => $response['errors']], 400);
        }

        $invoice = $response['data']['invoice'];

        return response()->json([
            'success' => true,
            'invoice' => $invoice
        ]);
    }

    /**
     * Step 5: Handle payment webhook from Jobber
     */
    public function handlePaymentWebhook(Request $request)
    {
        // Verify webhook signature (IMPORTANT for security)
        if (!$this->verifyWebhookSignature($request)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $eventType = $request->input('event');
        $invoiceData = $request->input('data.invoice', []);
        $paymentData = $request->input('data.payment', []);

        Log::info('Jobber webhook received', [
            'event' => $eventType,
            'invoice_id' => $invoiceData['id'] ?? null,
            'payment_id' => $paymentData['id'] ?? null,
        ]);

        switch ($eventType) {
            case 'payment.created':
                $this->handlePaymentSuccess($invoiceData, $paymentData);
                break;
            
            case 'payment.updated':
                $this->handlePaymentUpdate($invoiceData, $paymentData);
                break;
            
            case 'invoice.paid':
                $this->handleInvoicePaid($invoiceData);
                break;
        }

        return response()->json(['message' => 'Webhook processed'], 200);
    }

    
    /**
     * Complete payment flow - Create job, invoice, and send
     */
    public function completePaymentFlow(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string',
            'client_email' => 'required|email',
            'client_phone' => 'nullable|string',
            'service_address' => 'required|string',
            'job_title' => 'required|string',
            'job_description' => 'nullable|string',
            'line_items' => 'required|array',
            'line_items.*.name' => 'required|string',
            'line_items.*.quantity' => 'required|numeric|min:1',
            'line_items.*.unit_price' => 'required|numeric|min:0',
            'line_items.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        
        try {
            $user = auth()->user();

            $jobberToken = JobberOAuthToken::where('user_id', $user->id)->first();

            if (!$jobberToken) {
                throw new \Exception('No Jobber token found for this user');
            }

            // Step 1: Create or get client
            $clientId = $this->createOrGetClient($jobberToken->access_token, [
                'name' => $request->client_name,
                'email' => $request->client_email,
                'phone' => $request->client_phone,
            ]);

            if (!$clientId) {
                throw new \Exception('Failed to create or find client');
            }

            // Step 2: Create job directly
            $jobMutation = [
                'query' => 'mutation CreateJob($input: JobCreateInput!) {
                    jobCreate(input: $input) {
                        job {
                            id
                            jobNumber
                            title
                            status
                            client {
                                id
                                name
                            }
                        }
                        userErrors {
                            field
                            message
                        }
                    }
                }',
                'variables' => [
                    'input' => [
                        'clientId' => $clientId,
                        'title' => $request->job_title,
                        'description' => $request->job_description,
                    ]
                ]
            ];

            $jobResponse = $this->makeJobberRequest($jobberToken->access_token, $jobMutation);

            if (isset($jobResponse['errors']) || !empty($jobResponse['data']['jobCreate']['userErrors'])) {
                throw new \Exception('Failed to create job: ' . json_encode($jobResponse['errors'] ?? $jobResponse['data']['jobCreate']['userErrors']));
            }

            $job = $jobResponse['data']['jobCreate']['job'];

            // Step 3: Create invoice directly
            $lineItems = collect($request->line_items)->map(function ($item) {
                return [
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unitPrice' => intval($item['unit_price'] * 100), // Convert to cents
                    'description' => $item['description'] ?? '',
                ];
            })->toArray();

            $invoiceMutation = [
                'query' => 'mutation CreateInvoice($input: InvoiceCreateInput!) {
                    invoiceCreate(input: $input) {
                        invoice {
                            id
                            invoiceNumber
                            subject
                            total
                            status
                            publicUrl
                            paymentUrl
                        }
                        userErrors {
                            field
                            message
                        }
                    }
                }',
                'variables' => [
                    'input' => [
                        'jobId' => $job['id'],
                        'subject' => 'Invoice for ' . $job['title'],
                        'lineItems' => $lineItems,
                    ]
                ]
            ];

            $invoiceResponse = $this->makeJobberRequest($jobberToken->access_token, $invoiceMutation);

            if (isset($invoiceResponse['errors']) || !empty($invoiceResponse['data']['invoiceCreate']['userErrors'])) {
                throw new \Exception('Failed to create invoice: ' . json_encode($invoiceResponse['errors'] ?? $invoiceResponse['data']['invoiceCreate']['userErrors']));
            }

            $invoice = $invoiceResponse['data']['invoiceCreate']['invoice'];

            // Store invoice in your database
            $this->storeInvoiceLocally($invoice, $request->user()->id, $job['id']);

            // Step 4: Send Invoice
            $sendMutation = [
                'query' => 'mutation SendInvoice($input: InvoiceSendInput!) {
                    invoiceSend(input: $input) {
                        invoice {
                            id
                            status
                            sentAt
                            publicUrl
                            paymentUrl
                        }
                        userErrors {
                            field
                            message
                        }
                    }
                }',
                'variables' => [
                    'input' => [
                        'invoiceId' => $invoice['id']
                    ]
                ]
            ];

            $sendResponse = $this->makeJobberRequest($jobberToken->access_token, $sendMutation);

            if (isset($sendResponse['errors']) || !empty($sendResponse['data']['invoiceSend']['userErrors'])) {
                Log::warning('Failed to send invoice automatically', [
                    'invoice_id' => $invoice['id'],
                    'errors' => $sendResponse['errors'] ?? $sendResponse['data']['invoiceSend']['userErrors']
                ]);
                // Don't throw exception here, just log the warning
            } else {
                // Update invoice with sent status
                $invoice = $sendResponse['data']['invoiceSend']['invoice'];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'job' => $job,
                'invoice' => $invoice,
                'payment_url' => $invoice['paymentUrl'],
                'message' => 'Payment flow completed successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment flow failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Payment flow failed',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // Helper Methods

    private function makeJobberRequest($accessToken, $data)
    {
        $response = Http::withToken($accessToken)
            ->withHeaders([
                'X-JOBBER-GRAPHQL-VERSION' => $this->apiVersion,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])
            ->timeout(30) // Add timeout
            ->post($this->baseUrl, $data);

        // Log the full response for debugging
        Log::info('Jobber API Response', [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'body' => $response->body()
        ]);

        return $response->json();
    }

    private function createOrGetClient($accessToken, $clientData)
    {
        try {
            // First, try to find existing client by email
            $searchQuery = [
                'query' => 'query SearchClients($filter: ClientFilterInput) {
                    clients(filter: $filter, first: 10) {
                        nodes {
                            id
                            name
                            emails {
                                nodes {
                                    address
                                }
                            }
                        }
                    }
                }',
                'variables' => [
                    'filter' => [
                        'emails' => [$clientData['email']]
                    ]
                ]
            ];

            $response = $this->makeJobberRequest($accessToken, $searchQuery);
            
            // Log the search response for debugging
            Log::info('Client search response', ['response' => $response]);
            
            // Check for API errors first
            if (isset($response['errors'])) {
                Log::error('GraphQL errors in client search', ['errors' => $response['errors']]);
                return null;
            }
            
            // Check if we found an existing client
            if (isset($response['data']['clients']['nodes']) && !empty($response['data']['clients']['nodes'])) {
                $existingClient = $response['data']['clients']['nodes'][0];
                Log::info('Found existing client', ['client_id' => $existingClient['id']]);
                return $existingClient['id'];
            }

            // Create new client if not found
            Log::info('Creating new client', ['client_data' => $clientData]);
            
            // Split name into first and last name
            $nameParts = explode(' ', trim($clientData['name']), 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
            
            $clientMutation = [
                'query' => 'mutation CreateClient($input: ClientCreateInput!) {
                    clientCreate(input: $input) {
                        client {
                            id
                            name
                            emails {
                                nodes {
                                    address
                                }
                            }
                            phoneNumbers {
                                nodes {
                                    number
                                }
                            }
                        }
                        userErrors {
                            field
                            message
                        }
                    }
                }',
                'variables' => [
                    'input' => [
                        'firstName' => $firstName,
                        'lastName' => $lastName,
                        'emails' => [
                            [
                                'address' => $clientData['email'],
                                'description' => 'MAIN',
                                'primary' => true
                            ]
                        ],
                        'phoneNumbers' => !empty($clientData['phone']) ? [
                            [
                                'number' => $clientData['phone'],
                                'description' => 'MAIN',
                                'primary' => true
                            ]
                        ] : []
                    ]
                ]
            ];

            $response = $this->makeJobberRequest($accessToken, $clientMutation);
            
            // Log the creation response for debugging
            Log::info('Client creation response', ['response' => $response]);
            
            // Check for errors
            if (isset($response['errors'])) {
                Log::error('GraphQL errors in client creation', ['errors' => $response['errors']]);
                return null;
            }
            
            if (!empty($response['data']['clientCreate']['userErrors'])) {
                Log::error('User errors in client creation', ['errors' => $response['data']['clientCreate']['userErrors']]);
                return null;
            }
            
            if (isset($response['data']['clientCreate']['client']['id'])) {
                $clientId = $response['data']['clientCreate']['client']['id'];
                Log::info('Successfully created client', ['client_id' => $clientId]);
                return $clientId;
            }

            Log::error('No client ID returned from creation', ['response' => $response]);
            return null;

        } catch (\Exception $e) {
            Log::error('Exception in createOrGetClient', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    private function getJobDetails($accessToken, $jobId)
    {
        $query = [
            'query' => 'query GetJob($jobId: ID!) {
                job(id: $jobId) {
                    id
                    jobNumber
                    title
                    status
                    client {
                        id
                        name
                    }
                }
            }',
            'variables' => [
                'jobId' => $jobId
            ]
        ];

        $response = $this->makeJobberRequest($accessToken, $query);
        
        return $response['data']['job'] ?? null;
    }

    private function storeInvoiceLocally($invoice, $userId, $jobId)
    {
        // Store in your local database for tracking
        JobberInvoice::create([
            'user_id' => $userId,
            'jobber_invoice_id' => $invoice['id'],
            'jobber_job_id' => $jobId,
            'invoice_number' => $invoice['invoiceNumber'],
            'total' => $invoice['total'],
            'status' => $invoice['status'],
            'payment_url' => $invoice['paymentUrl'] ?? null,
            'public_url' => $invoice['publicUrl'] ?? null,
        ]);
    }

    private function verifyWebhookSignature(Request $request)
    {
        $signature = $request->header('X-Jobber-Signature');
        $payload = $request->getContent();
        $secret = config('services.jobber.webhook_secret'); // Add this to your config
        
        if (!$secret || !$signature) {
            return false;
        }
        
        $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    private function handlePaymentSuccess($invoiceData, $paymentData)
    {
        // Update your local database
        $invoice = JobberInvoice::where('jobber_invoice_id', $invoiceData['id'])->first();
        if ($invoice) {
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_amount' => $paymentData['amount'] ?? null,
            ]);
        }

        // Add your business logic here
        // - Send confirmation email
        // - Update job status
        // - Trigger any other workflows
        
        Log::info('Payment successful', [
            'invoice_id' => $invoiceData['id'],
            'amount' => $paymentData['amount'] ?? null,
        ]);
    }

    private function handlePaymentUpdate($invoiceData, $paymentData)
    {
        // Handle payment updates (refunds, etc.)
        Log::info('Payment updated', [
            'invoice_id' => $invoiceData['id'],
            'payment_id' => $paymentData['id'],
        ]);
    }

    private function handleInvoicePaid($invoiceData)
    {
        // Handle fully paid invoice
        $invoice = JobberInvoice::where('jobber_invoice_id', $invoiceData['id'])->first();
        if ($invoice) {
            $invoice->update(['status' => 'fully_paid']);
        }
        
        Log::info('Invoice fully paid', ['invoice_id' => $invoiceData['id']]);
    }
}