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
//Route::get('/user/list-user', 'UserController@index')->name('list.user');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/register', 'HomeController@getRegister')->name('register');
Route::post('/register', 'HomeController@postRegister')->name('post.register');
Route::get('/login', 'LoginController@getLogin')->name('login');
Route::post('/login', 'LoginController@postLogin')->name('post.login');
Route::get('/logout', 'LogoutController@getLogout')->name('logout');
Route::get('/forget-password', 'ForgotPasswordController@getforgetpassword')->name('forget.password');

Route::post('/user/delete/{name}/{id}', 'HomeController@postDelete')->name('user.postDelete');
Route::get('user/edit/{id}', 'HomeController@getEdit')->name('user.getEdit');
Route::post('user/edit/{id}', 'HomeController@postEdit')->name('user.postEdit');



