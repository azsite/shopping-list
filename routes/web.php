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
    return view('home');
});

Auth::routes();

Route::post('/get', 'ListsController@ajax_get')->name('get_list')
    ->middleware('auth')
;
Route::post('/save', 'ListsController@ajax_save')->name('save_list')
    ->middleware('auth')
;
Route::post('/delete', 'ListsController@ajax_delete')->name('delete_list')
    ->middleware('auth')
;
