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

Auth::routes();

Route::group(['middleware' => ['auth']], function(){

    Route::get('/', 'HomeController@index')->name('home');

    Route::resource('clients', 'ClientsController')->except(['show', 'edit', 'create']);

    Route::post('clients/search', 'ClientsController@search')->name('clients.search');

    Route::post('clients/{client}/addresses', 'AddressesController@store')->name('addresses.store');
    Route::put('clients/{client}/addresses/update', 'AddressesController@update')->name('addresses.update');
    Route::post('ajax/addresses', 'AddressesController@show')->name('addresses.show');

    Route::post('clients/{client}/phones', 'PhonesController@store')->name('phones.store');
    Route::put('clients/{client}/phones/update', 'PhonesController@update')->name('phones.update');
    Route::post('ajax/phones', 'PhonesController@show')->name('phones.show');

    Route::post('clients/{client}/emails', 'EmailsController@store')->name('emails.store');
    Route::put('clients/{client}/emails/update', 'EmailsController@update')->name('emails.update');
    Route::post('ajax/emails', 'EmailsController@show')->name('emails.show');

    Route::post('clients/{client}/services', 'ServicesController@store')->name('services.store');
    Route::put('clients/{client}/services/update', 'ServicesController@update')->name('services.update');
    Route::post('ajax/services', 'ServicesController@show')->name('services.show');

    Route::post('clients/{client}/notes', 'CaseNotesController@store')->name('notes.store');

    Route::get('clients/{client}/{panel}', 'ClientsController@show')->name('clients.show');

    Route::get('admin', 'AdminController@index')->name('admin.index');
    Route::get('admin/roles', 'AdminRolesController@index')->name('admin.roles.index');
    Route::post('admin/roles', 'AdminRolesController@store')->name('admin.roles.store');
    Route::put('admin/roles/update', 'AdminRolesController@update')->name('admin.roles.update');
    Route::post('ajax/roles', 'AdminRolesController@show')->name('admin.roles.show');

});
