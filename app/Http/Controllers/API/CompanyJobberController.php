<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\CompanyJobberToken;
use Carbon\Carbon;

class CompanyJobberController extends Controller
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    public function __construct()
    {
        $this->clientId = env('JOBBER_CLIENT_ID');
        $this->clientSecret = env('JOBBER_CLIENT_SECRET');
        $this->redirectUri = env('JOBBER_COMPANY_REDIRECT_URI', 'https://backend.binbearjunk.com/api/company/jobber/callback');
    }

    /**
     * Get company OAuth authorization URL for BinBear main account
     */
    public function getCompanyAuthUrl()
    {
        $scopes = [
            'JOBS_READ',
            'JOBS_WRITE', 
            'CLIENTS_READ',
            'CLIENTS_WRITE',
            'PROPERTIES_READ',
            'PROPERTIES_WRITE'
        ];

        $authUrl = 'https://api.getjobber.com/api/oauth/authorize?' . http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => implode(' ', $scopes),
            'state' => 'company_auth_' . time(),
        ]);

        return response()->json([
            'message' => 'Company authorization URL generated',
            'auth_url' => $authUrl,
            'instructions' => 'Visit this URL with the BinBear main account (contact@binbears.com) to authorize company-wide access',
            'redirect_uri' => $this->redirectUri
        ]);
    }

    /**
     * Handle company OAuth callback and store company tokens
     */
    public function handleCompanyCallback(Request $request)
    {
        $code = $request->input('code');

        if (!$code) {
            return response()->json(['error' => 'Authorization code not provided'], 400);
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

            // Deactivate any existing tokens and create new one
            CompanyJobberToken::where('is_active', true)->update(['is_active' => false]);

            $companyToken = CompanyJobberToken::create([
                'company_name' => 'BinBear Junk Removal',
                'company_email' => 'contact@binbears.com',
                'access_token' => $tokenData['access_token'],
                'refresh_token' => $tokenData['refresh_token'] ?? null,
                'expires_at' => Carbon::now()->addSeconds($tokenData['expires_in'] ?? 0),
                'is_active' => true
            ]);

            Log::info('Company Jobber OAuth token saved successfully.', [
                'company_email' => 'contact@binbears.com',
                'token_id' => $companyToken->id
            ]);

            return response()->json([
                'message' => 'Company authorization successful! All API calls will now use BinBear main account.',
                'company' => [
                    'name' => 'BinBear Junk Removal',
                    'email' => 'contact@binbears.com'
                ],
                'token_expires_at' => $companyToken->expires_at,
                'next_steps' => 'All clients created via API will now go to your main BinBear Jobber account'
            ]);

        } catch (\Exception $e) {
            Log::error('Company OAuth token exchange failed:', [
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
     * Refresh company access token
     */
    public function refreshCompanyToken()
    {
        $companyToken = CompanyJobberToken::getActiveToken();

        if (!$companyToken) {
            return response()->json([
                'error' => 'No active company token found',
                'message' => 'Please complete company OAuth authorization first'
            ], 404);
        }

        if (!$companyToken->refresh_token) {
            return response()->json([
                'error' => 'No refresh token available',
                'message' => 'Please re-authorize company access'
            ], 400);
        }

        $client = new Client();

        try {
            $response = $client->post('https://api.getjobber.com/api/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'refresh_token' => $companyToken->refresh_token,
                ]
            ]);

            $tokenData = json_decode($response->getBody()->getContents(), true);

            // Update the stored token
            $companyToken->update([
                'access_token' => $tokenData['access_token'],
                'refresh_token' => $tokenData['refresh_token'] ?? $companyToken->refresh_token,
                'expires_at' => Carbon::now()->addSeconds($tokenData['expires_in'] ?? 0),
            ]);

            Log::info('Company Jobber OAuth token refreshed successfully.');

            return response()->json([
                'message' => 'Company token refreshed successfully',
                'company' => [
                    'name' => $companyToken->company_name,
                    'email' => $companyToken->company_email
                ],
                'expires_at' => $companyToken->expires_at,
                'expires_in' => $tokenData['expires_in'] ?? 0
            ]);

        } catch (\Exception $e) {
            Log::error('Company token refresh failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to refresh company token',
                'message' => $e->getMessage(),
                'suggestion' => 'You may need to re-authorize company access'
            ], 500);
        }
    }

    /**
     * Get company token status
     */
    public function getCompanyTokenStatus()
    {
        $companyToken = CompanyJobberToken::getActiveToken();

        if (!$companyToken) {
            return response()->json([
                'status' => 'NOT_AUTHORIZED',
                'message' => 'No company Jobber token found',
                'next_step' => 'Call GET /api/company/jobber/auth to get authorization URL'
            ]);
        }

        $isExpired = $companyToken->expires_at <= Carbon::now();

        return response()->json([
            'status' => $isExpired ? 'EXPIRED' : 'ACTIVE',
            'company' => [
                'name' => $companyToken->company_name,
                'email' => $companyToken->company_email
            ],
            'expires_at' => $companyToken->expires_at,
            'is_expired' => $isExpired,
            'has_refresh_token' => !empty($companyToken->refresh_token),
            'next_step' => $isExpired ? 'Token expired, will auto-refresh on next API call' : 'Ready for company-wide Jobber operations'
        ]);
    }
}
