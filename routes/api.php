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

Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@register');
Route::post('forget-password', 'API\AuthController@resetPassword');

Route::group(['middleware' => ['auth:api', 'roles'], 'namespace' => 'API'], function() {
//    Route::group(['middleware' => ['auth:api', 'roles'], 'roles' => ['App-Users'],'namespace' => 'API'], function() {
    Route::post('games', 'GameController@getItems');
    Route::post('competitions', 'CompetitionController@getItems');
    Route::post('news', 'NewsController@getItems');
    Route::post('update/leaderboard', 'CompetitionLeaderBoardController@updateleaderboard');
    Route::post('category', 'CompetitionCategoryController@getItems');
    Route::post('previouswinner', 'PreviouswinnerController@getItems');
    Route::get('DashboardController', 'DashboardController@getItems');
    Route::post('getprofile', 'AuthController@MyProfile');
    Route::post('updateprofile', 'AuthController@ProfileUpdate');
});
