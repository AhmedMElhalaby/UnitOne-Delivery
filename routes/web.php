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


Route::post('store','Admin\DeliveryCrudController@storeDelev');
Route::get('paid/{id}','Admin\DeliveryCrudController@UpdateDelev');
Route::get('delete/{id}','Admin\DeliveryCrudController@DeleteDelev');

Route::group(['prefix' => 'admin', 'middleware' => ['admin'], 'namespace' => 'Admin'], function()
{
    CRUD::resource('delivery', 'DeliveryCrudController');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('check_out', 'HomeController@checkout')->name('home');
