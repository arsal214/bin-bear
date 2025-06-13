<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\API\ZipCodeController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\StripeController;
use App\Http\Controllers\API\JobberController;
use App\Http\Controllers\API\JobberOAuthController;
use App\Http\Controllers\API\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


/* ------------------------- Blogs  Routes ------------------------ */

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


/* ------------------------- Stripe  Routes ------------------------ */

Route::post('payment-key-generate', [StripeController::class, 'paymentKey']);
Route::post('process-payment', [StripeController::class, 'processStripePayment']);

// Authentication route
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    
    // OAuth routes
    Route::get('jobber/auth', [JobberOAuthController::class, 'redirectToJobber']);
    Route::get('jobber/callback', [JobberOAuthController::class, 'handleCallback']);
    Route::post('jobber/refresh', [JobberOAuthController::class, 'refreshToken']);
    
    // API routes (now require access_token)
    Route::post('create-client', [JobberController::class, 'createClient']);
    Route::get('clients/{jobberClientId}', [JobberController::class, 'getClient']);
    Route::get('clients/{jobberClientId}/properties', [JobberController::class, 'getClientProperties']);
    Route::get('get-available-times', [JobberController::class, 'getAvailableTimes']);
    Route::post('create-job-draft', [JobberController::class, 'createJobDraft']);
    Route::post('/clients/{jobberClientId}/properties', [JobberController::class, 'createProperty']);


    Route::get('/jobber/schema/property-create-input', [JobberController::class, 'introspectJobberSchema']);
});

Route::get('jobber/code-binbear', [JobberOAuthController::class, 'CodeBinBear']);
