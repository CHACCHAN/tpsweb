<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetEnvController;
use App\Http\Controllers\Auth\GetLoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API
// APP_NAMEの取得
Route::get('/get/env', function() {
    return response()->json(['ResponseData' => config('app.name')], 200);
});
// ログイン状況の取得
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/login/check', function() {
        try {
            if (auth()->check()) {
                return response()->json(['ResponseData' => true]);  
            } else {
                return response()->json(['ResponseData' => false]); 
            }
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return response()->json(['ResponseData' => false], 401);
        }
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});