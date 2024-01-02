<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Auth
// メール認証
Route::post('/auth/register', [UserController::class, 'emailAuther']);
// 新規登録
Route::post('/auth/register/create', [UserController::class, 'register']);
// ログイン
Route::post('/auth/login', [UserController::class, 'login']);
// ログアウト
Route::get('/auth/logout', [UserController::class, 'logout']);

Route::get('/laravel', fn() => view('welcome'));

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');