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
Route::resource('passports','PassportController');
Route::resource('users', 'UserController');

Auth::routes();
Route::group(['middleware' => 'auth_custom'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/logout', 'HomeController@getLogout')->name('logout');
    Route::post('/user/delete/{id}', 'HomeController@postDelete')->name('user.postDelete');
    Route::get('/user/edit/{id}', 'HomeController@getEdit')->name('user.getEdit');
    Route::post('/user/edit/{id}', 'HomeController@postEdit')->name('user.postEdit');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', 'HomeController@getRegister')->name('register');
    Route::post('/register', 'HomeController@postRegister')->name('post.register');
    Route::get('/login', 'HomeController@getLogin')->name('login');
    Route::post('/login', 'HomeController@postLogin')->name('post.login');
});


