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


/*
|--------------------------------------------------------------------------
| Facebook Auth Route
|--------------------------------------------------------------------------
|
*/

Route::get('login/{group_id}/facebook', 'FbAuth\LoginController@redirectToProvider');
Route::get('login/facebook/callback/{provider?}', 'FbAuth\LoginController@handleProviderCallback');
Route::get('callback/{provider?}', 'FbAuth\LoginController@handleCallback');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


/*
|--------------------------------------------------------------------------
| Facebook Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['namespace' => 'Facebook', 'middleware' => ['auth', 'admin']], function() {

	Route::resource('/pages', 'PageController', ['except' => ['show', 'edit', 'update']]);
	Route::get('/pages/{id}/posts', 'PageController@show');

	Route::resource('/facebook-posts.activity', 'ActivityController', ['only' => ['index', 'show']]);
	
	Route::get('/users/{group_id}', 'UserController@index');
	Route::resource('/users', 'UserController', ['except' => ['index', 'destroy', 'create', 'store']]);
	Route::get('/users/{id}/delete', 'UserController@destroy');
	Route::get('/users/{id}/activity', 'UserController@show');

});


/*
|--------------------------------------------------------------------------
| Twitter Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['namespace' => 'Twitter', 'middleware' => ['auth', 'admin']], function() {

	Route::resource('/twitter-posts.activity', 'HandleActivityController', ['only' => ['show', 'index']]);
	Route::get('/handles/{handle_id}/activity', 'HandleActivityController@showUserActivity');
	Route::resource('/handles', 'HandleController', ['except' => ['show', 'destroy']]);
	Route::get('/handles/{id}/posts', 'HandleController@show');
	Route::resource('/hashtags', 'HashtagController', ['except' => ['destroy']]);
	Route::get('/handles/{id}/delete', 'HandleController@destroy');
	Route::get('/hashtags/{id}/delete', 'HashtagController@destroy');

});

/*
|--------------------------------------------------------------------------
| Error Routes
|--------------------------------------------------------------------------
|
*/

Route::get('{any?}', function() {
	return view('layouts.error');
});
