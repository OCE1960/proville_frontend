<?php

/* use Illuminate\Http\Request;
use App\Http\Controllers\pageController; */

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

Route::get('activate/{id}', 'DashBoardController@activate');
Route::get('dashboard','DashBoardController@dashboard');


Route::group([

    'middleware' => 'api',
    // 'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::put('activate/{id}', 'AuthController@activate');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('sendPasswordResetLink', 'ResetPasswordController@sendEmail');
    Route::post('resetPassword', 'ChangePasswordController@process');

});

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 */