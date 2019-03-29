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

Route::get('/', 'AppController@index');

Route::middleware('auth')->group(function() {
    Route::get('/home', 'AppController@getHome')->name('home');
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/files/{file}', 'AppController@getHome');

});

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');

Route::post('/passwords/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('passwords/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('passwords/reset', 'Auth\ResetPasswordController@reset')->name('password.reset.post');

Route::middleware('auth', 'admin')->group(function() {
    Route::prefix('admin')->group(function() {
        Route::get('/dashboard', 'AppController@getDashboard');

        // files
        Route::get('/files', 'AppController@getDashboard');
        Route::get('/files/{file}', 'AppController@getDashboard');

        // modules
        Route::get('/modules', 'AppController@getDashboard');
        Route::get('/modules/{module}', 'AppController@getDashboard');

        // elements
        Route::get('/elements', 'AppController@getDashboard');
        Route::get('/elements/create', 'AppController@getDashboard');
        Route::get('/elements/{element}', 'AppController@getDashboard');

        //user
        Route::get('/users', 'AppController@getDashboard');

        // settings
        Route::get('/settings/icons', 'AppController@getDashboard');
    });
});
