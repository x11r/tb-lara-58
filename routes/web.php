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

//

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    // 管理画面メニューがほしい
    Route::get('news', 'Admin\NewsController@index');
    Route::get('news/create', 'Admin\NewsController@add');
    Route::post('news/create', 'Admin\NewsController@create');
    Route::get('news/edit', 'Admin\NewsController@edit');
    Route::post('news/edit', 'Admin\NewsController@update');
    Route::get('news/delete', 'Admin\NewsController@delete');

    Route::get('profile/create', 'Admin\ProfileController@add');
    Route::post('profile/create', 'Admin\ProfileController@create');
    Route::get('profile/edit/{profile_id}', 'Admin\ProfileController@edit');
    Route::post('profile/edit', 'Admin\ProfileController@update');
    Route::get('profile/detele', 'Admin\ProfileController@delete');
    Route::get('profile', 'Admin\ProfileController@index');
});

Route::get('rakuten', 'RakutenController@index');

Route::group(['prefix' => '/rakuten/hotelSearch/{large_class}/{middle_class}/{small_class}'], function() {
    Route::get('/{detail_class}', 'RakutenController@hotelSearch');
    Route::get('/', 'RakutenController@hotelSearch');
});

Route::get('rakuten/hotelDetail/{hotel_id}', 'RakutenController@hotelDetail');

Route::group(['prefix' => 'api/v1'], function() {
    Route::get('news/all', 'NewsController@indexJson');
    Route::get('rakuten/hotelArea', 'RakutenController@areaJson');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'NewsController@index');
