<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\Admin\QuillController;
use App\Models\Post;
use App\Models\PostCategory;

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

// API
// APP_NAMEの取得
Route::get('/get/env', function() {
    return response()->json(['ResponseData' => config('app.name')], 200);
});

// ログイン状況取得
Route::get('/auth/login/check', function() {
    try {
        if (auth()->check()) {
            return response()->json([
                'ResponseData' => true,
                'Adminstrator' => auth()->user()->administrator,
                'UserData'     => auth()->user(),
            ]);
        } else {
            return response()->json([
                'ResponseData' => false,
                'Adminstrator' => false,
                'UserData'     => false,
            ]); 
        }
    } catch (\Illuminate\Auth\AuthenticationException $e) {
        return response()->json([
            'ResponseData' => false,
            'Adminstrator' => false
        ], 401);
    }
});

// 投稿データの取得
Route::get('/get/postdata', function() {
    return response()->json([
        'responseData' => Post::get(),
    ], 200);
});
// 投稿データのカテゴリの取得
Route::get('/get/postcategory', function() {
    return response()->json([
        'responseData' => PostCategory::get(),
    ], 200);
});
// 投稿データの一時画像の保存
Route::post('/post/postdata/image/temp', [QuillController::class, 'saveImageTemp']);
// 投稿データの正版画像の保存
Route::post('/post/postdata/image', [QuillController::class, 'saveImage']);
// 投稿データのコンテンツ保存
Route::post('/post/postData/content', [QuillController::class, 'saveContent']);

// メール
// メール送信
Route::post('/email/post', [MailController::class, 'postEmail']);

Route::get('/laravel', fn() => view('welcome'));

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');