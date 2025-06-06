<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\JobberClient; // Import the JobberClient model
use App\Models\JobberOAuthToken; // Import the JobberOAuthToken model
use Carbon\Carbon; // Import Carbon for timestamps

class JobberController extends Controller
{
    /**
     * Get a valid Jobber access token for the authenticated user, refreshing if necessary.
     *
     * @return string|null The valid access token, or null if not available.
     */
    private function getValidAccessToken()
    {
        $user = auth()->user();

        if (!$user) {
            return null; // No authenticated user
        }

        $jobberToken = JobberOAuthToken::where('user_id', $user->id)->first();

        if (!$jobberToken) {
            return null; // No Jobber token found for this user
        }

        // Check if the access token is expired
        if ($jobberToken->expires_at <= Carbon::now()) {
            Log::info('Jobber access token expired. Attempting to refresh.', ['user_id' => $user->id]);
            // Token is expired, attempt to refresh
            $refreshedTokenData = $this->refreshJobberToken($jobberToken->refresh_token);

            if (isset($refreshedTokenData['access_token'])) {
                // Update the stored token
                $jobberToken->update([
                    'access_token' => $refreshedTokenData['access_token'],
                    'refresh_token' => $refreshedTokenData['refresh_token'] ?? $jobberToken->refresh_token, // Keep old refresh token if new one not provided
                    'expires_at' => Carbon::now()->addSeconds($refreshedTokenData['expires_in'] ?? 0),
                ]);
                 Log::info('Jobber access token refreshed and updated.', ['user_id' => $user->id]);
                return $refreshedTokenData['access_token'];
            } else {
                 Log::error('Failed to refresh Jobber access token.', ['user_id' => $user->id, 'response' => $refreshedTokenData]);
                return null; // Refresh failed
            }
        }

        // Access token is still valid
        return $jobberToken->access_token;
    }

    /**
     * Make a request to Jobber's OAuth token endpoint to refresh an access token.
     *
     * @param string $refreshToken The refresh token.
     * @return array|null The token data, or null on failure.
     */
    private function refreshJobberToken(string $refreshToken): ?array
    {
         // Note: You might want to move this logic to the JobberOAuthController
         // and call that controller's method here.

        $client = new Client();
        $clientId = env('JOBBER_CLIENT_ID'); // Assuming Client ID is in .env
        $clientSecret = env('JOBBER_CLIENT_SECRET'); // Assuming Client Secret is in .env

        if (empty($clientId) || empty($clientSecret)) {
             Log::error('Jobber client ID or secret not configured for token refresh.');
            return null;
        }

        try {
            $response = $client->post('https://api.getjobber.com/api/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'refresh_token' => $refreshToken,
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (\Exception $e) {
            Log::error('Jobber token refresh request failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return null;
        }
    }

    /**
     * Create client using valid OAuth token
     */
    public function createClient(Request $request)
    {
        // Extract client data from the request
        $clientData = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            // Remove access_token validation from request body
            // 'access_token' => 'required|string',
        ]);

        // Get a valid access token for the authenticated user
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            return response()->json(['message' => 'Jobber not authorized for this user.'], 403); // Or 401/400 depending on context
        }

        $jobberApiUrl = 'https://api.getjobber.com/api/graphql';

        // GraphQL mutation to create a client
        $graphqlMutation = [
            'query' => '
                mutation CreateClient($input: ClientCreateInput!) {
                    clientCreate(input: $input) {
                        client {
                            id
                            firstName
                            lastName
                            emails {
                                address
                            }
                        }
                        userErrors {
                            message
                        }
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'firstName' => $clientData['first_name'],
                    'lastName' => $clientData['last_name'],
                    'emails' => [
                        [
                            'address' => $clientData['email'],
                            'description' => 'MAIN',
                            'primary' => true
                        ]
                    ],
                    'phones' => [
                        [
                            'number' => $clientData['phone'],
                            'description' => 'MAIN',
                            'primary' => true
                        ]
                    ]
                ]
            ]
        ];

        $client = new Client();

        try {
            Log::info('Sending GraphQL mutation to Jobber API:', $graphqlMutation);

            $response = $client->post($jobberApiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20', // **IMPORTANT: Replace with the actual required API version from Jobber docs**
                ],
                'json' => $graphqlMutation,
            ]);

            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody()->getContents(), true);

            Log::info('Received response from Jobber GraphQL API:', [
                'status_code' => $statusCode, 
                'response' => $responseData
            ]);

            // Check for GraphQL errors
            if (isset($responseData['errors']) && !empty($responseData['errors'])) {
                return response()->json([
                    'message' => 'GraphQL errors occurred',
                    'errors' => $responseData['errors']
                ], 400);
            }

            // Check for user errors in the mutation response
            if (isset($responseData['data']['clientCreate']['userErrors']) && 
                !empty($responseData['data']['clientCreate']['userErrors'])) {
                return response()->json([
                    'message' => 'Client creation failed',
                    'errors' => $responseData['data']['clientCreate']['userErrors']
                ], 400);
            }

            // Success case
            if (isset($responseData['data']['clientCreate']['client'])) {
                $createdClient = $responseData['data']['clientCreate']['client'];

                // ** Save the client data to your database **
                try {
                    $user = auth()->user(); // Get the authenticated user

                    if ($user) {
                        // Check if a JobberClient record already exists for this user (moved here)
                        $existingJobberClient = JobberClient::where('user_id', $user->id)->first();

                        if ($existingJobberClient) {
                            // If a client already exists, return its data FROM LOCAL DB
                            Log::info('Jobber client already exists for user.', ['user_id' => $user->id, 'jobber_client_id' => $existingJobberClient->jobber_client_id]);
                            // Note: We are returning 200 here as it's a GET-like behavior (returning existing resource)
                            // but this endpoint is POST. You might adjust the status code or make this a GET endpoint
                            // if the primary purpose is idempotency rather than creation.
                             return response()->json([
                                'message' => 'Jobber client already exists',
                                'jobber_client_id' => $existingJobberClient->jobber_client_id,
                                'client_data' => [
                                    'id' => $existingJobberClient->jobber_client_id,
                                    'firstName' => $existingJobberClient->first_name,
                                    'lastName' => $existingJobberClient->last_name,
                                    'emails' => [['address' => $existingJobberClient->email]],
                                    // Add other fields if you store them and need them in the response
                                ]
                            ], 200);
                        }

                        JobberClient::create([
                           'user_id' => $user->id,
                           'jobber_client_id' => $createdClient['id'],
                           'first_name' => $createdClient['firstName'] ?? null,
                           'last_name' => $createdClient['lastName'] ?? null,
                           'email' => $createdClient['emails'][0]['address'] ?? null, // Assuming first email is main
                           // 'phone' => $createdClient['phones'][0]['number'] ?? null, // Add if you fetch phones
                        ]);
                        Log::info('Jobber client data saved to database.', ['jobber_client_id' => $createdClient['id'], 'user_id' => $user->id]);
                    } else {
                         // This else should theoretically not be reached if getValidAccessToken returns a token
                         Log::error('Unexpected error: Authenticated user not found after token retrieval.');
                         return response()->json(['message' => 'Internal authentication error.'], 500);
                    }

                } catch (\Exception $dbException) {
                    Log::error('Error saving Jobber client data to database:', [
                        'error' => $dbException->getMessage(),
                        'jobber_client_id' => $createdClient['id'],
                        'trace' => $dbException->getTraceAsString()
                    ]);
                    // You might choose to still return success from the API call
                    // since the client was created in Jobber, but log the DB error.
                }

                return response()->json([
                    'message' => 'Client created successfully in Jobber and saved to DB',
                    'jobber_client_id' => $createdClient['id'],
                    'client_data' => $createdClient
                ], 201);
            }

            return response()->json([
                'message' => 'Unexpected response from Jobber API',
                'response' => $responseData
            ], 500);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $errorBody = $e->getResponse()->getBody()->getContents();
            
            Log::error('Client error from Jobber API:', [
                'status_code' => $statusCode,
                'error_body' => $errorBody,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Client error when creating client in Jobber',
                'status_code' => $statusCode,
                'error' => $errorBody
            ], $statusCode);
            
        } catch (\Exception $e) {
            Log::error('General error creating client in Jobber:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Error creating client in Jobber',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get client details by Jobber ID using valid OAuth token
     */
    public function getClient(Request $request, $jobberClientId)
    {
        // Remove access_token validation from request body
        // $request->validate([
        //     'access_token' => 'required|string',
        // ]);

        // Get a valid access token for the authenticated user
        $accessToken = $this->getValidAccessToken();

         if (!$accessToken) {
            return response()->json(['message' => 'Jobber not authorized for this user.'], 403);
        }

        // $accessToken = $request->input('access_token'); // Remove this line

        $jobberApiUrl = 'https://api.getjobber.com/api/graphql';

        // GraphQL query to fetch client by ID
        $graphqlQuery = [
            'query' => '
                query GetClient($id: EncodedId!) {
                    client(id: $id) {
                        id
                        firstName
                        lastName
                        emails {
                            address
                        }
                        phones {
                            number
                        }
                        # Note: addresses field removed as it doesn\'t exist on Client type
                        # Common alternative fields you might want to try:
                        # billingAddress {
                        #     street
                        #     city
                        #     state
                        #     zip
                        #     country
                        # }
                        # serviceAddress {
                        #     street
                        #     city
                        #     state
                        #     zip
                        #     country
                        # }
                          # Add other client fields you need
                      }
                  }
              ',
            'variables' => [
                'id' => $jobberClientId,
            ]
        ];

        $client = new Client();

        try {
            Log::info('Sending GraphQL query to Jobber API to get client:', ['query' => $graphqlQuery, 'client_id' => $jobberClientId]);

            $response = $client->post($jobberApiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20', // **IMPORTANT: Use the same actual required API version**
                ],
                'json' => $graphqlQuery,
            ]);

            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody()->getContents(), true);

            Log::info('Received response from Jobber GraphQL API for get client:', [
                'status_code' => $statusCode,
                'response' => $responseData
            ]);

            // Check for GraphQL errors
            if (isset($responseData['errors']) && !empty($responseData['errors'])) {
                return response()->json([
                    'message' => 'GraphQL errors occurred while fetching client',
                    'errors' => $responseData['errors']
                ], 400);
            }

            // Check if client data is returned
            if (isset($responseData['data']['client'])) {
                $clientData = $responseData['data']['client'];
                if ($clientData) {
                     return response()->json(['message' => 'Client fetched successfully', 'client' => $clientData], 200);
                } else {
                     return response()->json(['message' => 'Client not found'], 404);
                }
            }

            // Unexpected response structure
            return response()->json([
                'message' => 'Unexpected response from Jobber API',
                'response' => $responseData
            ], 500);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $errorBody = $e->getResponse()->getBody()->getContents();
            
            Log::error('Client error when fetching client from Jobber API:', [
                'status_code' => $statusCode,
                'error_body' => $errorBody,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Client error when fetching client from Jobber',
                'status_code' => $statusCode,
                'error' => $errorBody
            ], $statusCode);
            
        } catch (\Exception $e) {
            Log::error('General error fetching client from Jobber:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Error fetching client from Jobber',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available time slots by fetching scheduled jobs from Jobber.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableTimes(Request $request)
    {
        // Get a valid access token for the authenticated user
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            return response()->json(['message' => 'Jobber not authorized for this user.'], 403); // Or 401/400 depending on context
        }

        $jobberApiUrl = 'https://api.getjobber.com/api/graphql';

        // GraphQL query to fetch scheduled jobs
        // You might want to add filters for date range, assigned employee, etc.
        $graphqlQuery = [
            'query' => '
                query GetJobs($first: Int, $after: String) {
                    jobs(first: $first, after: $after) {
                        nodes {
                            id
                            title
                            startAt
                            endAt
                            # Add other job fields you need to determine availability
                        }
                        pageInfo {
                            hasNextPage
                            endCursor
                        }
                    }
                }
            ',
            'variables' => [
                'first' => 50, // Fetch up to 50 jobs
                // Add variables for filtering (e.g., date range)
                // 'startDate' => '...',
                // 'endDate' => '...',
            ]
        ];

        $client = new Client();

        try {
            Log::info('Sending GraphQL query to Jobber API to get scheduled jobs.', ['query' => $graphqlQuery]);

            $response = $client->post($jobberApiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20', // **IMPORTANT: Use the same actual required API version**
                ],
                'json' => $graphqlQuery,
            ]);

            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody()->getContents(), true);

            Log::info('Received response from Jobber GraphQL API for scheduled jobs:', [
                'status_code' => $statusCode,
                'response' => $responseData
            ]);

            // Check for GraphQL errors
            if (isset($responseData['errors']) && !empty($responseData['errors'])) {
                return response()->json([
                    'message' => 'GraphQL errors occurred while fetching scheduled jobs',
                    'errors' => $responseData['errors']
                ], 400);
            }

            // Process the jobs data to determine available time slots
            // This is where you'd implement your availability logic
            $scheduledJobs = $responseData['data']['jobs']['nodes'] ?? [];
            $availableSlots = []; // Your logic to calculate available slots goes here

            return response()->json([
                'message' => 'Scheduled jobs fetched successfully',
                'scheduled_jobs' => $scheduledJobs,
                'available_slots' => $availableSlots, // Return your calculated available slots
            ], 200);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
             $statusCode = $e->getResponse()->getStatusCode();
            $errorBody = $e->getResponse()->getBody()->getContents();
            
            Log::error('Client error fetching scheduled jobs from Jobber API:', [
                'status_code' => $statusCode,
                'error_body' => $errorBody,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Client error when fetching scheduled jobs from Jobber',
                'status_code' => $statusCode,
                'error' => $errorBody
            ], $statusCode);

        } catch (\Exception $e) {
            Log::error('General error fetching scheduled jobs from Jobber:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Error fetching scheduled jobs from Jobber',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}