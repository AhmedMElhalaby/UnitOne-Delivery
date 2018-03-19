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

use App\Settings;

Route::get('/', function () {
    $KMP = (new Settings)->where('id',1)->first()->value;
    $MP = (new Settings)->where('id',2)->first()->value;
    return view('welcome',compact('KMP','MP'));
});
Route::post('store','Admin\DeliveryCrudController@storeDelev');
Route::group(['prefix' => 'admin', 'middleware' => ['admin'], 'namespace' => 'Admin'], function()
{
    CRUD::resource('delivery', 'DeliveryCrudController');
});