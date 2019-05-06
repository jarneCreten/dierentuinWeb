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

Route::get('data', 'dierenController@index')->name('data');

Route::get('data/getdata', 'dierenController@getdata')->name('data.getdata');

Route::post('data/postdata', 'dierenController@postdata')->name('data.postdata');

Route::get('data/fetchdata', 'dierenController@fetchdata')->name('data.fetchdata');

Route::get('data/removedata', 'dierenController@removedata')->name('data.removedata');