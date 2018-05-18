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

Auth::routes();

Route::middleware(['auth'])->group(function () {

	Route::get('/', function () {
	    return view('welcome');
	});

	Route::get('/home', 'HomeController@index')->name('home');
	

	Route::resource('help', 'HelpController');

	// Настройки
	Route::prefix('settings')->group(function () {

	    Route::get('/', function () {
	        return redirect()->route('users.index');
	    });

		Route::get('/users/get-user', 'Settings\UserController@getUsers')->name('get-user');

	    Route::resource('users', 'Settings\UserController')->names([
		    'index' => 'users.index',
		    'update' => 'users.update'
		]);



	});
	
});