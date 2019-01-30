<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get(
    '/index', function () {
        return view('index');
    }
);

// user
Route::group(
    ['prefix' => 'user'], function () {
        Route::get('/', 'UserController@index')->name('user');
        Route::get('/change-password', 'UserController@changePassword')->name('user.changePassword');
        Route::post('/change-password', 'UserController@changePasswordProcess');
    }
);

Auth::routes();

// home
Route::get('/', 'HomeController@index')->name('home');
Route::permanentRedirect('/home', '/');
Route::get('/men', 'HomeController@men');
Route::get('/women', 'HomeController@women');

// order
Route::group(
    ['prefix' => 'order'], function() {
        Route::get('/', 'OrderController@index')->name('order');
        Route::get('/{id}', 'OrderController@detail');
    }
);

// merchandise
Route::group(
    ['prefix' => 'merchandise/{id}'], function() {
        Route::get('/', 'MerchandiseController@show');
    }
);

// cart
Route::group(
    ['prefix' => 'shopping-cart'], function() {
        Route::get('/', 'CartController@show');
        Route::post('/', 'CartController@add');
        Route::get('detail', 'CartController@jsonDetail');
        Route::get('checkout', 'CartController@checkout')->middleware('auth');
        Route::get('confirm', function() { return redirect('/shopping-cart/checkout'); });
        Route::post('confirm', 'CartController@confirm')->middleware('auth');
        Route::post('checkout', 'CartController@checkoutProcess')->middleware('auth');
    }
);

// admin
Route::group(
    ['prefix' => 'admin'], function() {
        // Merchandise
        Route::permanentRedirect('/','/admin/merchandise');
        Route::get('/merchandise', 'Admin\MerchandiseController@index');
        Route::get('/merchandise/new', 'Admin\MerchandiseController@new');
        Route::post('/merchandise/new', 'Admin\MerchandiseController@newProcess');
        Route::group(
            ['prefix' => '/merchandise/{id}'], function() {
                Route::get('/', 'Admin\MerchandiseController@show');
                Route::delete('/', 'Admin\MerchandiseController@delete');
                Route::get('/edit', 'Admin\MerchandiseController@edit');
                Route::put('/', 'Admin\MerchandiseController@editProcess');
            }
        );
        // User
        Route::group(
            ['prefix' => '/user'], function() {
                Route::get('/', 'Admin\UserController@index');
                Route::post('/', 'Admin\UserController@add');
            }
        );
        // Transaction
        Route::group(
            ['prefix' => '/transaction'], function() {
                Route::get('/', 'Admin\TransactionController@index');
            }
        );
        // Category
        Route::group(
            ['prefix' => '/category'], function() {
                Route::get('/', 'Admin\CategoryController@index');
                Route::post('/add', 'Admin\CategoryController@add');
            }
        );
        // Inventory
        Route::group(
            ['prefix' => 'inventory'], function() {
                Route::get('/', 'Admin\InventoryController@index');
                Route::patch('/{id}', 'Admin\InventoryController@inStock');
            }
        );
    }
);
