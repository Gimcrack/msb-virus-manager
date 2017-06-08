<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('patterns', 'PatternController@index');
Route::get('patterns/{pattern}', 'PatternController@show');
Route::post('patterns/{pattern}/unpublish', 'PatternController@unpublish');
Route::post('patterns/{pattern}/publish', 'PatternController@publish');

Route::post('clients/{client}/logs', 'ClientLogEntryController@store');

Route::post('clients/{client}/matches', 'ClientMatchedFilesController@store');

Route::get('clients', 'ClientController@index');
Route::get('clients/{client}/heartbeat', 'ClientController@touch');
Route::get('clients/{client}', 'ClientController@show');
Route::patch('clients/{client}', 'ClientController@update');
Route::post('clients/{name}', 'ClientController@store');
