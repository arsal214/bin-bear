<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\JobberClient; // Import the JobberClient model
use App\Models\JobberOAuthToken; // Import the JobberOAuthToken model
use Carbon\Carbon; // Import Carbon for timestamps
use App\Models\JobberJob; // Import the JobberJob model

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
            return response()->json(['message' => 'Jobber not authorized for this user.'], 403);
        }

        $jobberApiUrl = 'https://api.getjobber.com/api/graphql';

        // GraphQL query to fetch scheduled jobs
        $graphqlQuery = [
            'query' => '
                query GetJobs($first: Int, $after: String) {
                    jobs(first: $first, after: $after) {
                        nodes {
                            id
                            title
                            startAt
                            endAt
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
            ]
        ];

        $client = new Client();

        try {
            Log::info('Fetching scheduled jobs from Jobber:', [
                'access_token' => $accessToken
            ]);

            $response = $client->post($jobberApiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20',
                ],
                'json' => $graphqlQuery,
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            // Check for GraphQL errors
            if (isset($responseData['errors'])) {
                return response()->json([
                    'message' => 'GraphQL errors occurred',
                    'errors' => $responseData['errors']
                ], 400);
            }

            // Get scheduled jobs
            $scheduledJobs = $responseData['data']['jobs']['nodes'] ?? [];
            
            // Convert scheduled jobs to time ranges
            $bookedRanges = collect($scheduledJobs)->map(function($job) {
                return [
                    'start' => Carbon::parse($job['startAt']),
                    'end' => Carbon::parse($job['endAt'])
                ];
            })->sortBy('start')->values();

            // Calculate available slots
            $availableSlots = $this->calculateAvailableSlots(
                Carbon::now(),
                Carbon::now()->endOfDay(),
                $bookedRanges,
                15
            );

            return response()->json([
                'message' => 'Available time slots calculated successfully',
                'available_slots' => $availableSlots,
                'scheduled_jobs' => $scheduledJobs
            ], 200);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error('Client error fetching scheduled jobs:', [
                'error' => $e->getMessage(),
                'response' => $e->getResponse()->getBody()->getContents()
            ]);
            
            return response()->json([
                'message' => 'Error fetching scheduled jobs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate available time slots based on booked ranges
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param Collection $bookedRanges
     * @param int $durationMinutes
     * @return array
     */
    private function calculateAvailableSlots($startDate, $endDate, $bookedRanges, $durationMinutes)
    {
        $availableSlots = [];
        $currentTime = $startDate->copy();
        
        // Define business hours (9 AM to 5 PM)
        $businessStartHour = 9;
        $businessEndHour = 17;

        while ($currentTime->lt($endDate)) {
            // Skip if outside business hours
            if ($currentTime->hour < $businessStartHour || $currentTime->hour >= $businessEndHour) {
                $currentTime->addMinutes(15); // Move to next 15-minute slot
                continue;
            }

            // Skip weekends
            if ($currentTime->isWeekend()) {
                $currentTime->addDay();
                $currentTime->setHour($businessStartHour)->setMinute(0);
                continue;
            }

            $slotEnd = $currentTime->copy()->addMinutes($durationMinutes);
            
            // Check if this slot overlaps with any booked ranges
            $isAvailable = true;
            foreach ($bookedRanges as $booked) {
                if ($currentTime->lt($booked['end']) && $slotEnd->gt($booked['start'])) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable && $slotEnd->lte($endDate)) {
                $availableSlots[] = [
                    'start' => $currentTime->toIso8601String(),
                    'end' => $slotEnd->toIso8601String(),
                    'duration_minutes' => $durationMinutes
                ];
            }

            $currentTime->addMinutes(15); // Move to next 15-minute slot
        }

        return $availableSlots;
    }

    /**
     * Create a draft job in Jobber
     */
    public function createJobDraft(Request $request)
    {
        // Validate the request (keep existing validation)
        $jobData = $request->validate([
            'jobber_client_id' => 'required|string',
            'jobber_property_id' => 'required|string',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'start_at' => 'date',
            'end_at' => 'date|after:start_at',
            'price' => 'nullable|numeric|min:0',
        ]);

        // Get access token (keep existing auth logic)
        $accessToken = $this->getValidAccessToken();
        if (!$accessToken) {
            return response()->json(['message' => 'Jobber not authorized for this user.'], 403);
        }

        $jobberApiUrl = 'https://api.getjobber.com/api/graphql';

        // Corrected GraphQL mutation with proper input type
        $graphqlMutation = [
            'query' => '
                mutation CreateJob($input: JobCreateAttributes!) {
                    jobCreate(input: $input) {
                        job {
                            id
                            title
                            jobNumber
                            instructions
                            startAt
                            endAt
                            jobStatus

                        }
                        userErrors {
                            message
                            path
                        }
                    }
                }
            ',
            'variables' => [
                'input' => [
                    'title' => $jobData['title'],
                    'propertyId' => $jobData['jobber_property_id'],
                    'instructions' => $jobData['description'] ?? null,
                    'scheduling' => [
                        'createVisits' => true,
                        'notifyTeam' => false,
                    ],
                    'invoicing' => [
                        'invoicingType' => 'FIXED_PRICE',
                        'invoicingSchedule' => 'ON_COMPLETION',
                    ]
                ]
            ]
        ];

        $client = new Client();

        try {
            Log::info('Sending corrected GraphQL mutation to Jobber:', $graphqlMutation);

            $response = $client->post($jobberApiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-04-16', // Updated to working version
                ],
                'json' => $graphqlMutation,
            ]);

            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody()->getContents(), true);

            // Check for GraphQL errors
            if (isset($responseData['errors'])) {
                return response()->json([
                    'message' => 'GraphQL errors occurred',
                    'errors' => $responseData['errors']
                ], 400);
            }

            // Check for user errors in the mutation response
            if (isset($responseData['data']['jobCreate']['userErrors']) && !empty($responseData['data']['jobCreate']['userErrors'])) {
                return response()->json([
                    'message' => 'Job creation failed',
                    'errors' => $responseData['data']['jobCreate']['userErrors']
                ], 400);
            }

            // Success case
            if (isset($responseData['data']['jobCreate']['job'])) {
                $createdJob = $responseData['data']['jobCreate']['job'];
                return response()->json([
                    'message' => 'Job draft created successfully',
                    'job' => $createdJob
                ], 201);
            }

            return response()->json([
                'message' => 'Unexpected response from Jobber API',
                'response' => $responseData
            ], 500);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $errorBody = $e->getResponse()->getBody()->getContents();
            
            Log::error('Client error creating job in Jobber:', [
                'status_code' => $statusCode,
                'error_body' => $errorBody,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Client error when creating job in Jobber',
                'status_code' => $statusCode,
                'error' => $errorBody
            ], $statusCode);
            
        } catch (\Exception $e) {
            Log::error('General error creating job in Jobber:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Error creating job in Jobber',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Get client properties from Jobber
     */
    public function getClientProperties(Request $request, $jobberClientId)
    {
        // Get a valid access token for the authenticated user
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            return response()->json(['message' => 'Jobber not authorized for this user.'], 403);
        }

        $jobberApiUrl = 'https://api.getjobber.com/api/graphql';

        // GraphQL query to fetch client properties
        // Updated to use correct field names for PropertyAddress
        $graphqlQuery = [
            'query' => '
                query GetClientProperties($clientId: EncodedId!) {
                    client(id: $clientId) {
                        id
                        properties {
                            id
                            name
                            address {
                                street
                                city
                                province
                                postalCode
                                country
                            }
                        }
                    }
                }
            ',
            'variables' => [
                'clientId' => $jobberClientId
            ]
        ];

        $client = new Client();

        try {
            Log::info('Sending GraphQL query to Jobber API to get client properties:', ['query' => $graphqlQuery]);

            $response = $client->post($jobberApiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20',
                ],
                'json' => $graphqlQuery,
            ]);

            $statusCode = $response->getStatusCode();
            $responseData = json_decode($response->getBody()->getContents(), true);

            Log::info('Received response from Jobber GraphQL API for client properties:', [
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

            // Check if client data is returned
            if (isset($responseData['data']['client'])) {
                $clientData = $responseData['data']['client'];
                if ($clientData) {
                    return response()->json([
                        'message' => 'Client properties fetched successfully',
                        'properties' => $clientData['properties'] ?? []
                    ], 200);
                } else {
                    return response()->json(['message' => 'Client not found'], 404);
                }
            }

            return response()->json([
                'message' => 'Unexpected response from Jobber API',
                'response' => $responseData
            ], 500);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $errorBody = $e->getResponse()->getBody()->getContents();
            
            Log::error('Client error fetching properties from Jobber:', [
                'status_code' => $statusCode,
                'error_body' => $errorBody,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Client error when fetching properties from Jobber',
                'status_code' => $statusCode,
                'error' => $errorBody
            ], $statusCode);
            
        } catch (\Exception $e) {
            Log::error('General error fetching properties from Jobber:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Error fetching properties from Jobber',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a property for a client in Jobber
     */
    public function createProperty(Request $request)
    {
        // Validate incoming request
        $data = $request->validate([
            'jobber_client_id' => 'required|string',
            'name' => 'nullable|string', // Property name if supported
            'street' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string',
        ]);

        // Get access token
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            return response()->json([
                'message' => 'Jobber not authorized for this user.'
            ], 403);
        }

        // Prepare GraphQL mutation with nested address object
        $graphqlQuery = [
            'query' => '
                mutation CreateProperty($clientId: EncodedId!, $input: PropertyCreateInput!) {
                    propertyCreate(clientId: $clientId, input: $input) {
                        properties {
                            id
                            address {
                                street
                                city
                                province
                                postalCode
                                country
                            }
                        }
                        userErrors {
                            message
                        }
                    }
                }
            ',
            'variables' => [
                'clientId' => $data['jobber_client_id'],
                'input' => [
                    // PropertyCreateInput expects a 'properties' array
                    'properties' => [
                        [
                            // PropertyAttributes structure
                            'name' => $data['name'] ?? null,
                            'address' => [
                                // AddressAttributes structure - correct field names from schema
                                'street1' => $data['street'],
                                'city' => $data['city'],
                                'province' => $data['province'],
                                'postalCode' => $data['postal_code'],
                                'country' => $data['country'],
                            ]
                        ]
                    ]
                ]
            ],
        ];

        try {
            $client = new \GuzzleHttp\Client();

            $response = $client->post('https://api.getjobber.com/api/graphql', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20',
                ],
                'json' => $graphqlQuery,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            if (!empty($body['errors'])) {
                return response()->json([
                    'message' => 'GraphQL errors occurred',
                    'errors' => $body['errors']
                ], 400);
            }

            if (!empty($body['data']['propertyCreate']['userErrors'])) {
                return response()->json([
                    'message' => 'Property creation failed',
                    'errors' => $body['data']['propertyCreate']['userErrors']
                ], 400);
            }

            if (!empty($body['data']['propertyCreate']['properties'])) {
                return response()->json([
                    'message' => 'Property created successfully',
                    'property' => $body['data']['propertyCreate']['properties'][0]
                ], 201);
            }

            return response()->json([
                'message' => 'Unexpected response from Jobber API',
                'response' => $body
            ], 500);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json([
                'message' => 'Client error when creating property',
                'status_code' => $e->getResponse()->getStatusCode(),
                'error' => $e->getResponse()->getBody()->getContents()
            ], $e->getResponse()->getStatusCode());

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating property',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    // BELOW ARE THE METHODS FOR GETTING KNOW THE GRAPHQL SCHEMA OF THE JOBBER APIs.



    /**
     * Get complete schema information for Jobber API
     */
    public function getFullSchema()
    {
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            return response()->json(['message' => 'Jobber not authorized for this user.'], 403);
        }

        $introspectionQuery = [
            'query' => '
                query FullIntrospection {
                    __schema {
                        types {
                            name
                            kind
                            description
                            fields {
                                name
                                description
                                args {
                                    name
                                    description
                                    type {
                                        kind
                                        name
                                        ofType {
                                            kind
                                            name
                                        }
                                    }
                                }
                                type {
                                    kind
                                    name
                                    ofType {
                                        kind
                                        name
                                    }
                                }
                            }
                            inputFields {
                                name
                                description
                                type {
                                    kind
                                    name
                                    ofType {
                                        kind
                                        name
                                    }
                                }
                            }
                        }
                    }
                }
            '
        ];

        try {
            $client = new Client();
            $response = $client->post('https://api.getjobber.com/api/graphql', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20',
                ],
                'json' => $introspectionQuery,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            
            return response()->json([
                'message' => 'Full schema retrieved',
                'schema' => $body['data']['__schema'] ?? null
            ]);

        } catch (\Exception $e) {
            Log::error('Error retrieving full schema:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Error retrieving full schema',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get JobCreateInput schema to understand required fields
     */
    public function getJobCreateInputSchema()
    {
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            return response()->json(['message' => 'Jobber not authorized for this user.'], 403);
        }

        $introspectionQuery = [
            'query' => '
                query IntrospectJobCreateInput {
                    __type(name: "JobCreateInput") {
                        name
                        inputFields {
                            name
                            type {
                                name
                                kind
                                ofType {
                                    name
                                    kind
                                }
                            }
                            description
                        }
                    }
                }
            '
        ];

        try {
            $client = new Client();
            $response = $client->post('https://api.getjobber.com/api/graphql', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20',
                ],
                'json' => $introspectionQuery,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            
            return response()->json([
                'message' => 'JobCreateInput schema retrieved',
                'schema' => $body['data']['__type'] ?? null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error introspecting JobCreateInput',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Job type schema to understand available fields
     */
    public function getJobTypeSchema()
    {
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            return response()->json(['message' => 'Jobber not authorized for this user.'], 403);
        }

        $introspectionQuery = [
            'query' => '
                query IntrospectJobType {
                    __type(name: "Job") {
                        name
                        fields {
                            name
                            type {
                                name
                                kind
                                ofType {
                                    name
                                    kind
                                }
                            }
                            description
                        }
                    }
                }
            '
        ];

        try {
            $client = new Client();
            $response = $client->post('https://api.getjobber.com/api/graphql', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20',
                ],
                'json' => $introspectionQuery,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            
            return response()->json([
                'message' => 'Job type schema retrieved',
                'schema' => $body['data']['__type'] ?? null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error introspecting Job type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Job schema to understand what fields are available
     */
    public function getJobSchema()
    {
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            return response()->json(['message' => 'Jobber not authorized for this user.'], 403);
        }

        $introspectionQuery = [
            'query' => '
                query GetJobSchema {
                    __type(name: "Job") {
                        name
                        fields {
                            name
                            type {
                                name
                                kind
                            }
                        }
                    }
                }
            '
        ];

        try {
            $client = new Client();
            $response = $client->post('https://api.getjobber.com/api/graphql', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20',
                ],
                'json' => $introspectionQuery,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            return response()->json([
                'message' => 'Job schema found',
                'schema' => $body['data']['__type'] ?? null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error getting Job schema',
                'error' => $e->getMessage()
            ], 500);
        }
    }






    /**
     * Get AddressAttributes schema to understand address field structure
     */
    public function getAddressAttributesSchema()
    {
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            return response()->json([
                'message' => 'Jobber not authorized for this user.'
            ], 403);
        }

        $introspectionQuery = [
            'query' => '
                query GetAddressAttributesSchema {
                    __schema {
                        types {
                            name
                            inputFields {
                                name
                                type {
                                    name
                                    kind
                                    ofType {
                                        name
                                        kind
                                    }
                                }
                                description
                            }
                        }
                    }
                }
            '
        ];

        try {
            $client = new \GuzzleHttp\Client();

            $response = $client->post('https://api.getjobber.com/api/graphql', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20',
                ],
                'json' => $introspectionQuery,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            // Get AddressAttributes schema
            $addressAttributes = collect($body['data']['__schema']['types'])
                ->firstWhere('name', 'AddressAttributes');

            return response()->json([
                'message' => 'AddressAttributes schema found',
                'schema' => $addressAttributes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error getting AddressAttributes schema',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get PropertyAttributes schema to understand what fields are needed
     */
    public function getPropertyAttributesSchema()
    {
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            return response()->json([
                'message' => 'Jobber not authorized for this user.'
            ], 403);
        }

        $introspectionQuery = [
            'query' => '
                query GetPropertyAttributesSchema {
                    __schema {
                        types {
                            name
                            inputFields {
                                name
                                type {
                                    name
                                    kind
                                    ofType {
                                        name
                                        kind
                                    }
                                }
                                description
                            }
                        }
                    }
                }
            '
        ];

        try {
            $client = new \GuzzleHttp\Client();

            $response = $client->post('https://api.getjobber.com/api/graphql', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20',
                ],
                'json' => $introspectionQuery,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            // Get PropertyAttributes schema
            $propertyAttributes = collect($body['data']['__schema']['types'])
                ->firstWhere('name', 'PropertyAttributes');

            return response()->json([
                'message' => 'PropertyAttributes schema found',
                'schema' => $propertyAttributes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error getting PropertyAttributes schema',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Introspect Jobber's GraphQL schema to find PropertyCreateInput fields
     */
    public function introspectPropertyCreateInput()
    {
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            return response()->json([
                'message' => 'Jobber not authorized for this user.'
            ], 403);
        }

        // GraphQL introspection query to find PropertyCreateInput structure
        $introspectionQuery = [
            'query' => '
                query IntrospectPropertyCreateInput {
                    __schema {
                        types {
                            name
                            inputFields {
                                name
                                type {
                                    name
                                    kind
                                    ofType {
                                        name
                                        kind
                                    }
                                }
                                description
                            }
                        }
                    }
                }
            '
        ];

        try {
            $client = new \GuzzleHttp\Client();

            $response = $client->post('https://api.getjobber.com/api/graphql', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20',
                ],
                'json' => $introspectionQuery,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            // Filter to find PropertyCreateInput type
            $propertyCreateInput = collect($body['data']['__schema']['types'])
                ->firstWhere('name', 'PropertyCreateInput');

            if ($propertyCreateInput) {
                return response()->json([
                    'message' => 'PropertyCreateInput schema found',
                    'schema' => $propertyCreateInput
                ]);
            }

            // If not found, return all input types for manual inspection
            $inputTypes = collect($body['data']['__schema']['types'])
                ->filter(function ($type) {
                    return str_contains($type['name'] ?? '', 'Property') && 
                        str_contains($type['name'] ?? '', 'Input');
                })
                ->values();

            return response()->json([
                'message' => 'PropertyCreateInput not found, but here are property-related input types',
                'input_types' => $inputTypes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error during introspection',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Alternative: Introspect the propertyCreate mutation specifically
     */
    public function introspectPropertyCreateMutation()
    {
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            return response()->json([
                'message' => 'Jobber not authorized for this user.'
            ], 403);
        }

        $introspectionQuery = [
            'query' => '
                query IntrospectMutations {
                    __schema {
                        mutationType {
                            fields {
                                name
                                args {
                                    name
                                    type {
                                        name
                                        kind
                                        inputFields {
                                            name
                                            type {
                                                name
                                                kind
                                            }
                                            description
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            '
        ];

        try {
            $client = new \GuzzleHttp\Client();

            $response = $client->post('https://api.getjobber.com/api/graphql', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20',
                ],
                'json' => $introspectionQuery,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            // Filter to find propertyCreate mutation
            $propertyCreateMutation = collect($body['data']['__schema']['mutationType']['fields'])
                ->firstWhere('name', 'propertyCreate');

            return response()->json([
                'message' => 'propertyCreate mutation schema',
                'mutation' => $propertyCreateMutation
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error during mutation introspection',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all schema types (helper method)
     */
    public function getAllSchemaTypes()
    {
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            return response()->json([
                'message' => 'Jobber not authorized for this user.'
            ], 403);
        }

        $introspectionQuery = [
            'query' => '
                query GetAllTypes {
                    __schema {
                        types {
                            name
                            kind
                            description
                        }
                    }
                }
            '
        ];

        try {
            $client = new \GuzzleHttp\Client();

            $response = $client->post('https://api.getjobber.com/api/graphql', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20',
                ],
                'json' => $introspectionQuery,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            // Filter property-related types
            $propertyTypes = collect($body['data']['__schema']['types'])
                ->filter(function ($type) {
                    return str_contains(strtolower($type['name'] ?? ''), 'property');
                })
                ->values();

            return response()->json([
                'message' => 'Property-related schema types',
                'types' => $propertyTypes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error getting schema types',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all mutations (helper method)
     */
    public function getAllMutations()
    {
        $accessToken = $this->getValidAccessToken();

        if (!$accessToken) {
            return response()->json([
                'message' => 'Jobber not authorized for this user.'
            ], 403);
        }

        $introspectionQuery = [
            'query' => '
                query GetAllMutations {
                    __schema {
                        mutationType {
                            fields {
                                name
                                description
                            }
                        }
                    }
                }
            '
        ];

        try {
            $client = new \GuzzleHttp\Client();

            $response = $client->post('https://api.getjobber.com/api/graphql', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20',
                ],
                'json' => $introspectionQuery,
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            // Filter property-related mutations
            $propertyMutations = collect($body['data']['__schema']['mutationType']['fields'])
                ->filter(function ($mutation) {
                    return str_contains(strtolower($mutation['name'] ?? ''), 'property');
                })
                ->values();

            return response()->json([
                'message' => 'Property-related mutations',
                'mutations' => $propertyMutations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error getting mutations',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // BELOW ARE THE METHODS FOR GETTING KNOW THE GRAPHQL SCHEMA OF THE JOBBER APIs FOR DRAFT.


    /**
     * Get the GraphQL mutation for creating a job draft
     */
    protected function getJobCreateMutation()
    {
        return [
            'query' => '
                mutation CreateJob($input: JobCreateInput!) {
                    jobCreate(input: $input) {
                        job {
                            id
                            title
                            jobNumber
                            instructions
                            notes {
                                content
                            }
                            scheduling {
                                startAt
                                endAt
                            }
                            invoicing {
                                total
                            }
                            jobStatus
                        }
                        userErrors {
                            message
                            path
                        }
                    }
                }
            '
        ];
    }

    /**
     * Get job details including pricing information
     */
    public function getJobDetails($jobId, $accessToken)
    {
        $jobberApiUrl = 'https://api.getjobber.com/api/graphql';
        
        $graphqlQuery = [
            'query' => '
                query GetJob($id: ID!) {
                    job(id: $id) {
                        id
                        title
                        description
                        jobNumber
                        createdAt
                        updatedAt
                        invoices {
                            edges {
                                node {
                                    id
                                    total
                                    subtotal
                                }
                            }
                        }
                        quotes {
                            edges {
                                node {
                                    id
                                    total
                                    subtotal
                                }
                            }
                        }
                    }
                }
            ',
            'variables' => [
                'id' => $jobId
            ]
        ];

        $client = new Client();
        
        try {
            $response = $client->post($jobberApiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'X-JOBBER-GRAPHQL-VERSION' => '2025-01-20',
                ],
                'json' => $graphqlQuery,
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            
            if (isset($responseData['data']['job'])) {
                return $responseData['data']['job'];
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Error fetching job details from Jobber:', [
                'job_id' => $jobId,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

}