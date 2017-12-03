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

Route::get('index','IndexController@index');
Route::get('yzm','IndexController@yzm');
Route::get('login','IndexController@login');
Route::post('login','IndexController@login_post');
Route::get('course','IndexController@course');
Route::any('kebiao','IndexController@kebiao');
Route::any('chenji','IndexController@chenji');
Route::any('test','IndexController@test');
