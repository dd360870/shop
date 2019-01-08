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

Route::group(
    ['prefix' => 'user'], function () {
        Route::get('/', 'UserController@index')->name('user');
        Route::get('/change-password', 'UserController@changePassword')->name('user.changePassword');
        Route::post('/change-password', 'UserController@changePasswordProcess');
    }
);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', function() {
    return redirect('home');
});

Route::group(
    ['prefix' => 'admin'], function() {
        Route::get('/', 'Admin\AdminController@index')->name('admin');
        Route::get('/merchandise', 'Admin\MerchandiseController@index');
        Route::get('/merchandise/new', 'Admin\MerchandiseController@new');
        Route::post('/merchandise/new', 'Admin\MerchandiseController@newProcess');
        Route::group(
            ['prefix' => '/merchandise/{id}'], function() {
                Route::get('/', 'Admin\MerchandiseController@show');
                Route::delete('/', 'Admin\MerchandiseController@show');
                Route::get('/edit', 'Admin\MerchandiseController@edit');
                Route::put('/', 'Admin\MerchandiseController@editProcess');
            }
        );
    }
);
