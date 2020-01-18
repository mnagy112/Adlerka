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

Route::group(['middleware' => ['isAdmin']], function () {
    Route::post('/articles', 'ArticlesController@store')->name('articles.store');
    Route::get('/articles/create', 'ArticlesController@create')->name('articles.create');
    Route::delete('/articles/{article}', 'ArticlesController@destroy')->name('articles.destroy');
    Route::put('/articles/{article}', 'ArticlesController@update')->name('articles.update');
    Route::get('/articles/{article}/edit', 'ArticlesController@edit')->name('articles.edit');

    Route::get('/users', 'UsersController@index')->name('users.index');
    Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');
    Route::post('/users/{user}/change-role/{role}', 'UsersController@changeRole')->name('users.changeRole');
});

Route::get('/', 'ArticlesController@index');

Route::get('/articles', 'ArticlesController@index')->name('articles.index');
Route::get('/articles/{article}', 'ArticlesController@show')->name('articles.show');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
