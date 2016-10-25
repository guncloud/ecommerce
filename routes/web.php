<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Auth::routes();
Route::get('/user/activation/{token}', 'Auth\RegisterController@activation');
Route::get('/get_user_logged', 'Auth\LoginController@getUserLogged');

Route::get('/home', 'HomeController@index');
// Route::resource('/user','UserController');
