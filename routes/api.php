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

Route::post("/create", "Engine@createContainer")->middleware('ipRestrict');
Route::post("/pageload", "Engine@pageLoad")->middleware('ipRestrict');
Route::post("/run", "Engine@run")->middleware('ipRestrict');
Route::post("/reset", "Engine@resetUserCode")->middleware('ipRestrict');
Route::post("/final", "Engine@setFinalCode")->middleware('ipRestrict');
Route::post("/update", "Engine@updateContainerRunnerApplication")->middleware('ipRestrict');

