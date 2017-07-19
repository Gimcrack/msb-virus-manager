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

Route::post("builds",'ClientController@build');

Route::get("users", "UserController@index");
Route::post("users", "UserController@store");
Route::delete("users/{user}", "UserController@destroy");
Route::post("users/{user}/promote", "UserController@promote");

Route::post('exemptions', 'ExemptionController@create');
Route::post('exemptions/{exemption}/unpublish', 'ExemptionController@unpublish');
Route::post('exemptions/{exemption}/publish', 'ExemptionController@publish');
Route::delete('exemptions/{exemption}', 'ExemptionController@destroy');

Route::delete('patterns/{pattern}', 'PatternController@destroy');
Route::post('patterns/{pattern}/unpublish', 'PatternController@unpublish');
Route::post('patterns/{pattern}/publish', 'PatternController@publish');

Route::delete('clients/{client}', 'ClientController@destroy');
Route::post('clients/{client}/matches/{match}/mute', 'ClientMatchedFilesController@mute');
Route::post('clients/{client}/matches/{match}/unmute', 'ClientMatchedFilesController@unmute');

Route::post('clients/{client}/matches/{match}/acknowledge', 'ClientMatchedFilesController@acknowledge');
Route::post('matches/acknowledge', 'MatchedFileController@acknowledge');

Route::post('clients/{client}/upgrade', 'ClientController@upgrade');
Route::post('clients/{client}/scan', 'ClientController@scan');


Route::post('admin-password-reset', 'ClientPasswordResetController@multiRequest');
Route::post('clients/{client}/admin-password-reset', 'ClientPasswordResetController@request');