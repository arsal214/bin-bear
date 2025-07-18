<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{BlogController, BookingController, CouponController, ZipCodeController, CategoryController, StripeController, JobberController, JobberOAuthController, AuthController, JobberPaymentController, CompanyJobberController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

/* ------------------------- Blogs Routes ------------------------ */
Route::apiResource('blogs', BlogController::class);
Route::get('blog/is-popular', [BlogController::class, 'isPopular']);

Route::apiResource('coupons', CouponController::class);
Route::apiResource('zip-codes', ZipCodeController::class);

Route::apiResource('bookings', BookingController::class);
Route::get('getPrice', [BookingController::class, 'getPrice']);

Route::apiResource('categories', CategoryController::class);
Route::get('category-page/{id}', [CategoryController::class, 'serviceCategory']);
Route::get('allCategories', [CategoryController::class, 'allCategories']);
Route::get('allList', [CategoryController::class, 'allList']);
Route::get('subCategoryByID/{id}', [CategoryController::class, 'subCategory']);
Route::get('category/is-popular', [CategoryController::class, 'isPopular']);

/* ------------------------- Stripe Routes ------------------------ */
Route::post('payment-key-generate', [StripeController::class, 'paymentKey']);
Route::post('process-payment', [StripeController::class, 'processStripePayment']);

/* ------------------------- Auth Routes ------------------------ */
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    // Auth routes (protected)
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh-token', [AuthController::class, 'refreshToken']);
    
    // Company Jobber OAuth routes (centralized approach)
    Route::prefix('company/jobber')->group(function () {
        Route::get('auth', [CompanyJobberController::class, 'getCompanyAuthUrl']);
        Route::get('callback', [CompanyJobberController::class, 'handleCompanyCallback']);
        Route::post('refresh', [CompanyJobberController::class, 'refreshCompanyToken']);
        Route::get('status', [CompanyJobberController::class, 'getCompanyTokenStatus']);
    });
    
    // Company-level Jobber API routes (uses BinBear main account)
    Route::post('company/create-client', [JobberController::class, 'createClientForCompany']);
    
    // OAuth routes
    Route::get('jobber/auth', [JobberOAuthController::class, 'redirectToJobber']);
    Route::get('jobber/callback', [JobberOAuthController::class, 'handleCallback']);
    Route::post('jobber/refresh', [JobberOAuthController::class, 'refreshToken']);
    Route::post('jobber/auto-refresh', [JobberOAuthController::class, 'autoRefreshToken']);
    Route::get('jobber/debug-stored-tokens', [JobberOAuthController::class, 'debugStoredTokens']);

    // Jobber API routes
    Route::post('create-client', [JobberController::class, 'createClient']);
    Route::get('clients/{jobberClientId}', [JobberController::class, 'getClient']);
    Route::get('clients/{jobberClientId}/properties', [JobberController::class, 'getClientProperties']);
    Route::get('get-available-times', [JobberController::class, 'getAvailableTimes']);
    Route::post('create-job-draft', [JobberController::class, 'createJobDraft']);
    Route::post('/clients/{jobberClientId}/properties', [JobberController::class, 'createProperty']);
    Route::get('/jobber/schema/property-create-input', [JobberController::class, 'introspectJobberSchema']);
    
    // Debug route to check OAuth status
    Route::get('jobber/oauth-status', [JobberController::class, 'checkOAuthStatus']);
    Route::get('jobber/debug-tokens', [JobberController::class, 'debugAllTokens']);
    Route::get('jobber/check-account', [JobberController::class, 'checkConnectedAccount']);

    // New Jobber Payment Integration
    // Route::post('/jobber/create-invoice', [JobberController::class, 'createInvoice']);
    // Route::post('/jobber/webhook/payment-success', [JobberController::class, 'paymentSuccessWebhook']);

    // Route::post('/jobber/test-job-exists', [JobberController::class, 'testJobExists']);


    // // In your routes file
    // Route::get('/test-jobber', [JobberController::class, 'testJobberConnection']);
    // Route::get('/debug-jobber-auth', [JobberController::class, 'debugJobberAuth']);


    Route::post('/create-invoice', [JobberPaymentController::class, 'createInvoice']);
    Route::post('/send-invoice', [JobberPaymentController::class, 'sendInvoice']);
    Route::get('/invoice-status', [JobberPaymentController::class, 'getInvoiceStatus']);
    
    // Complete flow (recommended)
    Route::post('/complete-payment-flow', [JobberPaymentController::class, 'completePaymentFlow']);
    
    // Get user's invoices
    Route::get('/invoices', [JobberPaymentController::class, 'getUserInvoices']);
});

// Webhook endpoint (no authentication needed)
Route::post('/webhook/jobber/payment', [JobberPaymentController::class, 'handlePaymentWebhook']);

// Optional: OAuth routes for Jobber authentication
Route::prefix('auth/jobber')->group(function () {
    Route::get('/redirect', [JobberPaymentController::class, 'redirectToJobber']);
    Route::get('/callback', [JobberPaymentController::class, 'handleJobberCallback']);
});

// Fixed callback route to match redirect URI
Route::get('jobber/code-binbear', [JobberOAuthController::class, 'handleCallback']);
