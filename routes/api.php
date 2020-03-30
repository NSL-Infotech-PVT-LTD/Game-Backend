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
//Route::post('register', 'API\AuthController@register');
Route::post('register', 'API\AuthController@socialRegister');
Route::post('forget-password', 'API\AuthController@resetPassword');


Route::group(['middleware' => ['auth:api', 'roles','VerifyEmail'], 'namespace' => 'API'], function() {
//    Route::group(['middleware' => ['auth:api', 'roles'], 'roles' => ['App-Users'],'namespace' => 'API'], function() {
   
    Route::get('Dashboard', 'DashboardController@getItems');
    Route::get('test-push', 'DashboardController@testPush');

    Route::post('logout', 'AuthController@logout');
    Route::get('getprofile', 'AuthController@MyProfile');
    Route::post('updateprofile', 'AuthController@ProfileUpdate');

    Route::post('games', 'GameController@getItems');
    Route::post('news', 'NewsController@getItems');
    Route::post('update/leaderboard', 'CompetitionLeaderBoardController@updateleaderboard');
    Route::post('previouswinner', 'PreviouswinnerController@getItems');
    Route::post('banners', 'BannersController@getItems');
    Route::post('getMeta', 'MetasController@getMeta');

    Route::post('competitions', 'CompetitionController@getItems');
    Route::post('get-competition', 'CompetitionController@getItem');
    Route::post('play-competition-enable', 'CompetitionController@CheckStatusCompetition');



//    Route::post('user-competition', 'CompetitionLeaderBoardController@UserCompetition');
//    Route::post('get-competition', 'CompetitionLeaderBoardController@GetLeaderBoardById');
    Route::post('category', 'CompetitionCategoryController@getItems');

    Route::post('user-competition', 'CompetitionUserController@GetMyCompetition');
    Route::post('competition-leadboard', 'CompetitionUserController@getMyCompetitionUsers');
    Route::post('play-competition', 'CompetitionUserController@playCompetitionCreate');
    Route::post('play-competition-token', 'CompetitionUserController@playCompetitionCreateWithToken');
    Route::post('play-competition-update', 'CompetitionUserController@playCompetitionUpdate');
    
});
