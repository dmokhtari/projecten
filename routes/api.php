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
Route::post('/auth/user/update_password', 'API\AuthController@update_password');
Route::post('/auth/user/update', 'API\AuthController@update');
Route::get('/auth/user/show', 'API\AuthController@show');

Route::get('/dashboard/users', 'API\DashboardController@getUsers');

Route::apiResource('files', 'API\FileController');
Route::apiResource('modules', 'API\ModuleController');
Route::apiResource('elements', 'API\ElementController');
Route::apiResource('subelements', 'API\SubElementController');
Route::apiResource('users', 'API\UserController');
Route::apiResource('roles', 'API\RoleController');

//rankings
Route::put('/files/ranking/{file}', 'API\FileController@rank');
Route::put('/modules/ranking/{module}', 'API\ModuleController@rank');
Route::put('/elements/ranking/{element}', 'API\ElementController@rank');

Route::post('/users/import', 'API\UserController@import');



// settings
Route::prefix('settings')->group(function() {
    Route::apiResource('icons', 'API\IconController');
    Route::apiResource('documentations', 'API\DocumentationController');
});
