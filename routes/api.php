<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\API\ZipCodeController;
use App\Http\Controllers\API\CategoryController;

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

Route::apiResource('categories', CategoryController::class);
Route::get('category-page/{id}', [CategoryController::class, 'serviceCategory']);
Route::get('allCategories', [CategoryController::class, 'allCategories']);
Route::get('allList', [CategoryController::class, 'allList']);
Route::get('subCategoryByID/{id}', [CategoryController::class, 'subCategory']);
Route::get('category/is-popular', [CategoryController::class, 'isPopular']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
