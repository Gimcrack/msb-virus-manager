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


Route::post("users/{user}/promote", "UserController@promote");

Route::post('exemptions/{exemption}/unpublish', 'ExemptionController@unpublish');
Route::post('exemptions/{exemption}/publish', 'ExemptionController@publish');

Route::post('patterns/{pattern}/unpublish', 'PatternController@unpublish');
Route::post('patterns/{pattern}/publish', 'PatternController@publish');