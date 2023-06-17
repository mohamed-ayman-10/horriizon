<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Auth
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/user-update', [AuthController::class, 'updateUser']);
});

// Home

Route::get('slider',[HomeController::class,'All_Slider']);
Route::get('about',[HomeController::class,'about']);


// Categories
Route::get('categories', [HomeController::class, 'category']);
Route::get('category/{id}', [HomeController::class, 'findCategory']);


// Products
Route::get('products', [HomeController::class, 'products']);
Route::get('productWithCategory', [HomeController::class, 'productWithCategory']);
Route::get('productWithCategory/{id}',[HomeController::class,'productWithCategoryId']);

// Auth Vendor
Route::prefix('vendor/auth')->controller(\App\Http\Controllers\Api\Vendor\AuthController::class)
->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout');
    Route::post('/refresh', 'refresh');
    Route::get('/user-profile', 'userProfile');
    Route::post('/vendor-update', 'updateVendor');
});

// Card
Route::prefix('card')->controller(\App\Http\Controllers\Api\CardController::class)->group(function (){
    Route::get('/{id}','my_card');
    Route::post('add','add_to_card');
    Route::get('delete_product/{id}','delete_product_in_my_card');
});

// PaymentMyfatoorah
Route::get('callback',[\App\Http\Controllers\MyFatoorahController::class,'callback`']);

Route::middleware('jwt.verify')->group(function () {

    Route::prefix('vendor/products')->controller(\App\Http\Controllers\Api\Vendor\ProductController::class)
    ->group(function () {

        Route::post('/', 'show');
        Route::post('/create', 'store');
        Route::post('{id}/update', 'update');
        Route::post('{id}/delete', 'destroy');

    });

});

// Governorates
Route::get('governorates', function () {
    $governorates = \App\Models\Governorate::all();
    return \App\Traits\GeneralApi::returnData('201', 'Success', $governorates);
});
