<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// api v1
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function(){
    Route::middleware('api-key')->group(function(){
        Route::apiResource('form-submission', 'FormSubmissionController')->only('index');
        Route::apiResource('response', 'ResponseController')->only('store');
    });
});

// api v2
Route::group(['prefix' => 'v2', 'namespace' => 'App\Http\Controllers\Api\V2'], function(){
    Route::middleware('api-key')->group(function(){
        Route::apiResource('form-submission', 'FormSubmissionController')->only('index');
    });
});
