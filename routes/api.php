<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BlogController;

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



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
