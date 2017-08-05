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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('login/{group_id}/facebook', 'FbAuth\LoginController@redirectToProvider');
Route::get('login/facebook/callback/{provider?}', 'FbAuth\LoginController@handleProviderCallback');
Route::get('callback/{provider?}', 'FbAuth\LoginController@handleCallback');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function() {
	Route::resource('/pages', 'PageController');
	Route::resource('/pages.posts', 'PostController');
	Route::resource('/facebook-posts.activity', 'ActivityController');
	// Route::resource('/twitter-posts.activity', 'ActivityController');
	Route::resource('/handles', 'HandleController');
	Route::resource('/handles.posts', 'HandlePostController');
	Route::resource('/hashtags', 'HashtagController');
	Route::get('/handles/{id}/delete', 'HandleController@destroy');
	Route::get('/hashtags/{id}/delete', 'HashtagController@destroy');

});

Route::group(['namespace' => 'Twitter', 'middleware' => ['auth', 'admin']], function() {
	Route::resource('/twitter-posts.activity', 'HandleActivityController');
	Route::get('/handles/{handle_id}/activity', 'HandleActivityController@showUserActivity');


});

// EAAZApcJGTENsBABE2nF6fsydYvvNZAeqNaLJYNnAhYrqTcQLbHG6xI9I6jS6aGVjaWRpCToHaqEQkVu3jRpr8peJ6ulGgaJ9gCagzYjNQuZCcARYrG1lLZBOqzsQHC19oahaDdrzARZC8mEZBFdTmZCP37lieCrs4fMeSFZBEfgPVQZDZD