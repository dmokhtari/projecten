<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/auth/user/role', 'AppController@getUserRole');

//Route::middleware('admin:api')->group(function() {
//    Route::get('/testing', 'AppController@getTest');
//});

Route::apiResource('modules', 'API\ModuleController');
Route::apiResource('users', 'API\UserController');
Route::post('/users/import', 'API\UserController@import');

Route::apiResource('roles', 'API\RoleController');
