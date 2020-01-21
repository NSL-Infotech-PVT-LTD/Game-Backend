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

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();
//Route::get('home', function () {
//    return redirect('/admin');
//});
Route::get('/home', 'HomeController@index')->name('home');




Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'roles'], 'roles' => ['Super-Admin']], function () {
    Route::get('home', 'HomeController@index')->name('home');

    Route::get('/', 'Admin\AdminController@index');
    Route::resource('roles', 'Admin\RolesController');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::resource('users', 'Admin\UsersController');
    Route::resource('pages', 'Admin\PagesController');
    Route::resource('activitylogs', 'Admin\ActivityLogsController')->only([
        'index', 'show', 'destroy'
    ]);
    Route::resource('settings', 'Admin\SettingsController');
    Route::get('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@getGenerator']);
    Route::post('generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@postGenerator']);
    Route::resource('game', 'Admin\GameController');
    Route::resource('competition', 'Admin\CompetitionController');
    Route::any('hotcompetition', 'Admin\CompetitionController@AllhotCompetition')->name('competition.hotcompetition');

    Route::resource('news', 'Admin\NewsController');
    Route::resource('compition-lead-board', 'Admin\CompitionLeadBoardController');
    Route::post('competition/change-status', 'Admin\CompetitionController@changeStatus')->name('competition.changeStatus');
    Route::post('competition/hot-competition', 'Admin\CompetitionController@hotCompetition')->name('competition.hotCompetition');
    Route::post('game/change-status', 'Admin\GameController@changeStatus')->name('game.changeStatus');
    Route::post('news/change-status', 'Admin\NewsController@changeStatus')->name('news.changeStatus');
    Route::post('users/change-status', 'Admin\UsersController@changeStatus')->name('users.changeStatus');
    Route::post('competition/confirm-winner', 'Admin\CompetitionController@confirmWinner')->name('competition.confirmWinner');
    Route::post('competition/show', 'Admin\CompetitionController@show')->name('competition.show');

    Route::resource('competition-categories', 'Admin\CompetitionCategoriesController');
    Route::post('competition-categories/change-status', 'Admin\NewsController@changeStatus')->name('competition-categories.changeStatus');
    Route::resource('previouswinner', 'Admin\PreviouswinnerController');
//    Route::resource('hotcompetition', 'Admin\CompetitionController@gethotCompetition');
    Route::post('previouswinner/change-status', 'Admin\PreviouswinnerController@changeStatus')->name('previouswinner.changeStatus');
    Route::get('hot-competition', 'Admin\CompetitionController@AllhotCompetition');

    Route::resource('banners', 'Admin\BannersController');
    Route::get('transaction/{id}', 'Admin\UsersController@abc');
    Route::resource('metas', 'Admin\MetasController');
    
});






