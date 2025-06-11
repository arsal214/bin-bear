<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ZipCodeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\BookingController;
use Illuminate\Support\Facades\Route;


/**
 * Profile Routes.
 */
Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'edit')->name('profile.edit');
    Route::patch('/profile', 'update')->name('profile.update');
    // Route::delete('/profile', 'destroy')->name('profile.destroy');
});

/**
 * Permissions Routes.
 */
Route::prefix('permissions')->as('permissions.')->group(function () {
    /* ------------------------- Staff Permission Routes ------------------------ */
    Route::resource('staff', PermissionController::class)->except(['show', 'delete']);
});

/**
 * Roles Routes.
 */
Route::prefix('roles')->as('roles.')->group(function () {
    /* ------------------------- Staff Roles Routes ------------------------ */
    Route::get('/staff/list', [RoleController::class, 'list'])->name('staff.list');
    Route::resource('staff', RoleController::class);
});
/**
 * Users Routes.
 */
Route::prefix('users')->as('users.')->group(function () {

    /* ------------------------- Admin  Routes ------------------------ */

    Route::patch('staff/change/{id}', 'StaffController@change')->name('staff.change');
    Route::get('staff/list', 'StaffController@list')->name('staff.list');
    Route::resource('staff', StaffController::class);


});


Route::prefix('catalog')->as('catalog.')->group(function () {
    /* -------------------------  Category Routes ------------------------ */
    Route::patch('category/change/{id}', 'CategoryController@change')->name('category.change');
    Route::get('category/list', 'CategoryController@list')->name('category.list');
    Route::get('get-subcategories/{categoryId}', [CategoryController::class, 'getSubcategories']);
    Route::get('sub-category', 'CategoryController@subCategory')->name('sub-category');
    Route::post('sub-category', 'CategoryController@subCategoryStore')->name('sub-category');
    Route::resource('category', CategoryController::class);
});




/**
 * Coupon Routes.
 */
Route::get('coupons/list', 'CouponController@list')->name('coupons.list');
Route::resource('coupons', CouponController::class)->except('show');


/**
 * Zip Routes.
 */
Route::get('zip-codes/list', 'ZipCodeController@list')->name('zip-codes.list');
Route::resource('zip-codes', ZipCodeController::class)->except('show');


/**
 * Zip Routes.
 */
Route::get('bookings/list', 'BookingController@list')->name('bookings.list');
Route::resource('bookings', BookingController::class);



/**
 * Pages Routes.
 */
Route::prefix('pages')->as('pages.')->group(function () {
    /* -------------------------  Blogs Routes ------------------------ */
    Route::patch('blogs/change/{id}', 'BlogController@change')->name('blogs.change');
    Route::get('blogs/list', 'BlogController@list')->name('blogs.list');
    Route::resource('blogs', BlogController::class);

});


/**
 * Settings Routes.
 */
Route::prefix('settings')->as('settings.')->group(function () {
    Route::controller(SettingController::class)->group(function () {
        Route::post('store', 'store')->name('store');
        Route::get('admin', 'admin')->name('admin');
    });
});
