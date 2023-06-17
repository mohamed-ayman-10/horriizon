<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "Admin" middleware group. Now create something great!
|
*/

// Auth

Route::controller('AuthController')->middleware('guest:admin')->name('auth.')->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('postLogin', 'postLogin')->name('postLogin');
    Route::get('register', 'register')->name('register');
    Route::post('postRegister', 'postRegister')->name('postRegister');
});


Route::get('/', function () {
    return view('admin.index');

})->name('index')->middleware('auth:admin');

Route::middleware('auth:admin')->group(function () {
    // Logout
    Route::get('logout', 'AuthController@logout')->name('logout');
});


Route::middleware('auth:admin')->group(function () {
    Route::get('profile/{id}', [\App\Http\Controllers\ProfileController::class, 'show_profile_admin'])->name('show_profile');
    Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'save_profile_admin'])->name('save_profile');
    Route::get('/vendor', [\App\Http\Controllers\AdminVendorController::class, 'show_vendor'])->name('show_vendor');
    Route::get('/vendor/{id}', [\App\Http\Controllers\AdminVendorController::class, 'show_product_vendor'])->name('show_product_vendor');
    Route::get('/product/sharing/{id}', [\App\Http\Controllers\AdminVendorController::class, 'sharing_product_vendor'])->name('sharing_product_vendor');
    Route::get('/product/unsharing/{id}', [\App\Http\Controllers\AdminVendorController::class, 'unsharing_product_vendor'])->name('unsharing_product_vendor');
    Route::post('/add/percentage/product', [\App\Http\Controllers\AdminVendorController::class, 'add_percentage_product'])->name('add_percentage_product');
    Route::get('/all/product/unsharing', [\App\Http\Controllers\AdminVendorController::class, 'all_product_unsharing'])->name('all_product_unsharing');
    Route::get('get/vendor/{id}', [\App\Http\Controllers\AdminVendorController::class, 'get_vendor'])->name('get_vendor');
    Route::post('/update/vendor', [\App\Http\Controllers\AdminVendorController::class, 'update_vendor'])->name('update_vendor');
    Route::post('/save/vendor', [\App\Http\Controllers\AdminVendorController::class, 'save_vendor'])->name('save_vendor');
    Route::get('delete/vendor/{id}', [\App\Http\Controllers\AdminVendorController::class, 'delete_vendor'])->name('delete_vendor');
    Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('get_home');
    Route::post('/home', [\App\Http\Controllers\HomeController::class, 'update'])->name('update_home');
    Route::get('all_product',[\App\Http\Controllers\Admin\ProductController::class,'All_product'])->name('all_products');


});

//Slider
Route::middleware('auth:admin')->group(function (){
    Route::get('home/slider', [\App\Http\Controllers\Admin\SliderController::class, 'index'])->name('get_slider');
    Route::post('save/slider', [\App\Http\Controllers\Admin\SliderController::class, 'create'])->name('create_slider');
    Route::post('update/slider', [\App\Http\Controllers\Admin\SliderController::class, 'update'])->name('update_slider');
    Route::get('delete/slider/{id}', [\App\Http\Controllers\Admin\SliderController::class, 'delete'])->name('delete_slider');
});

// Products
Route::prefix('products')->name('product.')->controller('productController')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/store', 'store')->name('store');
    Route::post('/update', 'update')->name('update');
    Route::get('/{id}/destroy', 'destroy')->name('destroy');
    Route::get('{id}/images', 'images')->name('images');
    Route::post('{id}/updateImage', 'updateImage')->name('updateImage');
    Route::post('/storeImages', 'storeImages')->name('storeImages');
    Route::get('{id}/destroyImage', 'destroyImage')->name('destroyImage');
})->middleware('auth:admin');

// Categories
Route::middleware('auth:admin')->prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('/');
    Route::post('create/', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('create');
    Route::post('update/', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('update');
    Route::get('delete/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('destroy');
});

//User
Route::middleware('auth:admin')->controller(\App\Http\Controllers\Admin\UserController::class)->group(function () {
    Route::get('user/','index')->name('get_user');
    Route::post('store/user','store')->name('user_save');
    Route::post('update/user','update')->name('user_update');
    Route::get('delete/user/{id}','delete_user')->name('delete_user');
});
