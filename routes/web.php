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
//Route::resource('passports','PassportController');
//Route::resource('users', 'UserController');

Auth::routes();
Route::group(['prefix' => 'user', 'middleware' => ['auth_custom:web']], function () {
    Route::get('/home', 'HomeController@index')->name('user.home');
    Route::get('/logout', 'HomeController@getLogout')->name('user.logout');
    Route::post('/delete/{id}', 'HomeController@postDelete')->name('user.postDelete');
    Route::get('/edit/{id}', 'HomeController@getEdit')->name('user.getEdit');
    Route::post('/edit/{id}', 'HomeController@postEdit')->name('user.postEdit');
    Route::get('/search/{name}', 'HomeController@getSearch')->name('user.getSearch');
});

Route::group(['prefix' => 'posts', 'middleware' => ['auth_custom:web']], function () {
    Route::get('/index', 'PostsController@getAllPost')->name('post.getAllPost');
    Route::get('/create', 'PostsController@getCreatePost')->name('get.createPost');
    Route::post('/create', 'PostsController@postCreatePost')->name('post.createPost');
    Route::post('/delete/{id}', 'PostsController@postDeletePost')->name('post.deletePost');
    Route::get('/edit/{id}', 'PostsController@getEditPost')->name('get.editPost');
    Route::post('/edit/{id}', 'PostsController@postEditPost')->name('post.editPost');

});

Route::group(['middleware' => ['auth_custom:web1', 'a', 'b']], function () {
    Route::group(['prefix' => 'user1'], function () {
        Route::get('/home', 'HomeController@index')->name('user1.home');
        Route::get('/logout', 'HomeController@getLogout')->name('user1.logout');
        Route::post('/user/delete/{id}', 'HomeController@postDelete')->name('user1.postDelete');
        Route::get('/user/edit/{id}', 'HomeController@getEdit')->name('user1.getEdit');
        Route::post('/user/edit/{id}', 'HomeController@postEdit')->name('user1.postEdit');
    });
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', 'HomeController@getRegister')->name('register');
    Route::post('/register', 'HomeController@postRegister')->name('post.register');
    Route::get('/login', 'HomeController@getLogin')->name('login');
    Route::post('/login', 'HomeController@postLogin')->name('post.login');
    Route::get('/login2', 'Users2Controller@getLogin')->name('login2');
    Route::post('/login2', 'Users2Controller@postLogin')->name('post.login2');
});

Route::post('/user/search', 'HomeController@postSearch')->name('user.postSearch');

