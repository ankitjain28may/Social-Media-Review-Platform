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
	Route::resource('/posts.activity', 'ActivityController');
	Route::get('/posts/{post_id}/users-{activity?}', 'PostUserController@index');
	Route::resource('/report', 'ReportingController');
	Route::resource('/twitter-posts', 'TwitterHandleActivityController');
	Route::resource('/handles', 'HandleController');
	Route::get('/handles/{id}/delete', 'HandleController@destroy');

});

// EAAZApcJGTENsBABE2nF6fsydYvvNZAeqNaLJYNnAhYrqTcQLbHG6xI9I6jS6aGVjaWRpCToHaqEQkVu3jRpr8peJ6ulGgaJ9gCagzYjNQuZCcARYrG1lLZBOqzsQHC19oahaDdrzARZC8mEZBFdTmZCP37lieCrs4fMeSFZBEfgPVQZDZD