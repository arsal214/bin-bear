<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\JobberOAuthToken;
use Carbon\Carbon;

class JobberOAuthController extends Controller
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    public function __construct()
    {
        $this->clientId = env('JOBBER_CLIENT_ID');
        $this->clientSecret = env('JOBBER_CLIENT_SECRET');
        // $this->clientId = "2dc3079c-8a3d-4810-a95c-4678cc67e486";
        // $this->clientSecret = "c359ae51c968c5a7acfcb2306f60d00aad55043de66b7f57625435ceb8a3ab67";
        
        // $this->redirectUri = env('JOBBER_REDIRECT_URI', 'http://binbear-backend-laragon.test/api/jobber/callback');
        $this->redirectUri = env('JOBBER_REDIRECT_URI', 'https://backend.binbearjunk.com/api/jobber/code-binbear');
    }

    /**
     * Step 1: Redirect user to Jobber for authorization
     */
    public function redirectToJobber()
    {
        $scopes = [
            'JOBS_READ',
            'JOBS_WRITE', 
            'CLIENTS_READ',
            'CLIENTS_WRITE'
        ];

        $authUrl = 'https://api.getjobber.com/api/oauth/authorize?' . http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => implode(' ', $scopes),
            'state' => csrf_token(), // CSRF protection
        ]);

        return response()->json([
            'auth_url' => $authUrl,
            'message' => 'Visit this URL to authorize the app'
        ]);
    }

    /**
     * Step 2: Handle callback from Jobber and exchange code for token
     */
    public function handleCallback(Request $request)
    {
        $code = $request->input('code');
        $state = $request->input('state');

        if (!$code) {
            return response()->json(['error' => 'Authorization code not provided'], 400);
        }

        // Verify CSRF token (optional but recommended)
        if ($state !== csrf_token()) {
            Log::warning('CSRF token mismatch in OAuth callback');
        }

        $client = new Client();

        try {
            // Exchange authorization code for access token
            $response = $client->post('https://api.getjobber.com/api/oauth/token', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'code' => $code,
                    'redirect_uri' => $this->redirectUri,
                ]
            ]);

            $tokenData = json_decode($response->getBody()->getContents(), true);

            // Store the tokens securely (you might want to save to database)
            Log::info('OAuth tokens received:', $tokenData);

            $user = auth()->user();

            if ($user) {
                // Save or update the Jobber OAuth token for the user
                JobberOAuthToken::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'access_token' => $tokenData['access_token'],
                        'refresh_token' => $tokenData['refresh_token'] ?? null,
                        'expires_at' => Carbon::now()->addSeconds($tokenData['expires_in'] ?? 0),
                    ]
                );
                Log::info('Jobber OAuth token saved to database.', ['user_id' => $user->id]);
            } else {
                Log::warning('Attempted to save Jobber OAuth token without authenticated user.');
            }

            return response()->json([
                'message' => 'Authorization successful',
                'access_token' => $tokenData['access_token'],
                'refresh_token' => $tokenData['refresh_token'] ?? null,
                'expires_in' => $tokenData['expires_in'] ?? null,
                'token_type' => $tokenData['token_type'] ?? 'Bearer'
            ]);

        } catch (\Exception $e) {
            Log::error('OAuth token exchange failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to exchange authorization code for token',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Step 3: Refresh expired access token
     */
    public function refreshToken(Request $request)
    {
        $refreshToken = $request->input('refresh_token');

        if (!$refreshToken) {
            return response()->json(['error' => 'Refresh token required'], 400);
        }

        $client = new Client();

        try {
            $response = $client->post('https://api.getjobber.com/api/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'refresh_token' => $refreshToken,
                ]
            ]);

            $tokenData = json_decode($response->getBody()->getContents(), true);

            // Find the user's token record and update it
            $user = auth()->user();

            if ($user) {
                $jobberToken = JobberOAuthToken::where('user_id', $user->id)->first();

                if ($jobberToken) {
                    $jobberToken->update([
                        'access_token' => $tokenData['access_token'],
                        'refresh_token' => $tokenData['refresh_token'] ?? $jobberToken->refresh_token,
                        'expires_at' => Carbon::now()->addSeconds($tokenData['expires_in'] ?? 0),
                    ]);
                    Log::info('Jobber OAuth token refreshed and saved to database.', ['user_id' => $user->id]);
                } else {
                    Log::warning('Attempted to refresh Jobber OAuth token for user with no existing record.', ['user_id' => $user->id]);
                }
            } else {
                Log::warning('Attempted to refresh Jobber OAuth token without authenticated user.');
            }

            return response()->json([
                'message' => 'Token refreshed successfully',
                'access_token' => $tokenData['access_token'],
                'refresh_token' => $tokenData['refresh_token'] ?? null,
                'expires_in' => $tokenData['expires_in'] ?? null,
            ]);

        } catch (\Exception $e) {
            Log::error('Token refresh failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to refresh token',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Auto-refresh token for current user (improved version)
     */
    public function autoRefreshToken(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $jobberToken = JobberOAuthToken::where('user_id', $user->id)->first();

        if (!$jobberToken) {
            return response()->json([
                'error' => 'No Jobber OAuth token found for this user',
                'message' => 'Please complete OAuth authorization first'
            ], 404);
        }

        if (!$jobberToken->refresh_token) {
            return response()->json([
                'error' => 'No refresh token available',
                'message' => 'Please re-authorize with Jobber'
            ], 400);
        }

        $client = new Client();

        try {
            $response = $client->post('https://api.getjobber.com/api/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'refresh_token' => $jobberToken->refresh_token,
                ]
            ]);

            $tokenData = json_decode($response->getBody()->getContents(), true);

            // Update the stored token
            $jobberToken->update([
                'access_token' => $tokenData['access_token'],
                'refresh_token' => $tokenData['refresh_token'] ?? $jobberToken->refresh_token,
                'expires_at' => Carbon::now()->addSeconds($tokenData['expires_in'] ?? 0),
            ]);

            Log::info('Jobber OAuth token auto-refreshed successfully.', ['user_id' => $user->id]);

            return response()->json([
                'message' => 'Token refreshed successfully',
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email
                ],
                'token_info' => [
                    'expires_at' => $jobberToken->expires_at,
                    'expires_in' => $tokenData['expires_in'] ?? 0,
                    'token_type' => 'Bearer'
                ],
                'next_step' => 'Token refreshed! You can now make API calls to Jobber.'
            ]);

        } catch (\Exception $e) {
            Log::error('Auto token refresh failed:', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to refresh token automatically',
                'message' => $e->getMessage(),
                'suggestion' => 'You may need to re-authorize with Jobber'
            ], 500);
        }
    }


    /**
     * Debug: Check stored Jobber OAuth tokens for current user
     */
    public function debugStoredTokens(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $jobberToken = JobberOAuthToken::where('user_id', $user->id)->first();

        if (!$jobberToken) {
            return response()->json([
                'message' => 'No Jobber OAuth token found for this user',
                'suggestion' => 'Please complete OAuth authorization first via /api/jobber/auth'
            ], 404);
        }

        return response()->json([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'token_exists' => true,
            'has_access_token' => !empty($jobberToken->access_token),
            'has_refresh_token' => !empty($jobberToken->refresh_token),
            'access_token_preview' => $jobberToken->access_token ? substr($jobberToken->access_token, 0, 10) . '...' : null,
            'refresh_token_preview' => $jobberToken->refresh_token ? substr($jobberToken->refresh_token, 0, 10) . '...' : null,
            'expires_at' => $jobberToken->expires_at,
            'is_expired' => $jobberToken->expires_at ? $jobberToken->expires_at->isPast() : null,
            'created_at' => $jobberToken->created_at,
            'updated_at' => $jobberToken->updated_at,
        ]);
    }

    /**
     * Handle the OAuth callback - redirects to main callback handler
     */
    public function CodeBinBear(Request $request)
    {
        // This method handles the callback from Jobber OAuth
        return $this->handleCallback($request);
    }
}