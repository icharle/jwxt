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

//主页面
Route::get('index','IndexController@index');

//登录
Route::get('yzm','IndexController@yzm');
Route::get('login','IndexController@login');
Route::post('login','IndexController@login_post');

//课表
Route::get('course','IndexController@course');
Route::any('kebiao','IndexController@kebiao');

//成绩
Route::get('score','IndexController@score');
Route::any('chengji','IndexController@chengji');

//预约教室
Route::any('classroom','IndexController@classroom');

//预约教室审核结果
Route::any('classroomshenhe','IndexController@classroomshenhe');

//预约教室申请原因
Route::any('classbecause','IndexController@classbecause');

//选课
Route::any('qk','IndexController@GetPhysical');
