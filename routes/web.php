<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\Admin\QuillController;
use App\Http\Controllers\Admin\PostingController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PickUpController;
use App\Http\Controllers\DiscordController;
use App\Models\User;
use App\Models\PickUp;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Media;
use App\Models\MediaGroup;
use App\Models\DiscordMedia;
use App\Models\DiscordPost;
use GuzzleHttp\Psr7\Query;

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
Route::get('/get/env', function() { return response()->json([ 'ResponseData' => config('app.name') ], 200);});
// Laravelバージョン取得
Route::get('/get/env/laravel', function() { return response()->json([ 'responseData' => Application::VERSION ], 200);});
// PHPバージョン取得
Route::get('/get/env/php', function() { return response()->json([ 'responseData' => PHP_VERSION ], 200);});
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

// ユーザIDとユーザネームを取得
Route::get('/auth/get', function() { return response()->json([ 'responseData' => User::get(['id', 'name']) ], 200);});

// Admin Home
// ピックアップ
// ピックアップ取得
Route::get('/get/pickup', function(){ return response()->json([ 'responseData' => PickUp::get() ], 200);});
// ピックアップDB数取得
Route::get('/get/pickup/sumCount', function(){ return response()->json([ 'responseData' => PickUp::count() ], 200);});
// ピックアップ条件取得
Route::post('/post/pickup/focus', [PickUpController::class, 'getPickUp']);
// ピックアップ作成
Route::post('/post/pickup/create', [PickUpController::class, 'makePickUp']);
// ピックアップ削除
Route::post('/post/pickup/delete', [PickUpController::class, 'deletePickUp']);

// Discord
// ポスト設定の取得
Route::get('/get/discord/post', function() { return response()->json([ 'responseData' => DiscordPost::first() ]);});
// メディア設定の取得
Route::get('/get/discord/media', function() { return response()->json([ 'responseData' => DiscordMedia::first() ]);});
// ポスト設定の更新
Route::post('/post/discord/post', [DiscordController::class, 'updatePost']);
// メディア設定の更新
Route::post('/post/discord/media', [DiscordController::class, 'updateMedia']);
// ポストWebhook
Route::post('/post/discord/post/webhook', [DiscordController::class, 'PostWebhook']);
// メディアWebhook
Route::post('/post/discord/media/webhook', [DiscordController::class, 'MediaWebhook']);
// Discordに投稿
Route::post('/post/discord/send', [DiscordController::class, 'postDiscord']);

// Admin Posting
// カテゴリ
// カテゴリの新規作成
Route::post('/post/postcategory/create', [PostingController::class, 'makeNewCategory']);
// カテゴリのタイトル更新
Route::post('/post/postcategory/update', [PostingController::class, 'updateCategoryTitle']);
// カテゴリの削除
Route::post('/post/postcategory/delete', [PostingController::class, 'deleteCategory']);

// プロジェクト
// プロジェクトの新規作成
Route::post('/post/postdata/create', [PostingController::class, 'makeNewProject']);
// プロジェクトのタイトル更新
Route::post('/post/postdata/update', [PostingController::class, 'updateProjectTitle']);
// プロジェクトの削除
Route::post('/post/postdata/delete', [PostingController::class, 'deleteProject']);
// プロジェクトの移動
Route::post('/post/postdata/move', [PostingController::class, 'moveProject']);
// プロジェクトの複製
Route::post('/post/postdata/copy', [PostingController::class, 'copyProject']);
// プロジェクトの公開設定更新
Route::post('/post/postdata/public', [PostingController::class, 'updateProjectPublic']);

// 投稿データの取得
Route::get('/get/postdata', function() { return response()->json(['responseData' => Post::get()], 200);});
// 投稿データのカテゴリの取得
Route::get('/get/postcategory', function() { return response()->json(['responseData' => PostCategory::get()], 200);});
// 投稿データの一時画像の削除
Route::post('/post/postdata/image/remove', [QuillController::class, 'removeImageTemp']);
// 投稿データの正版画像の保存
Route::post('/post/postdata/image', [QuillController::class, 'saveImage']);
// 投稿データのコンテンツ保存
Route::post('/post/postdata/content', [QuillController::class, 'saveContent']);

// Admin Media
// メディアグループ取得
Route::get('/get/mediagroup', function() { return response()->json(['responseData' => MediaGroup::latest()->get()], 200);});
// メディア取得
Route::get('/get/media', function() { return response()->json(['responseData' => Media::latest()->get()], 200);});
// メディアグループ作成
Route::post('/post/mediagroup/create', [MediaController::class, 'makeNewMediaGroup']);
// メディアグループ更新
Route::post('/post/mediagroup/update', [MediaController::class, 'updateMediaGroup']);
// メディアグループ削除
Route::post('/post/mediagroup/delete', [MediaController::class, 'deleteMediaGroup']);
// メディア投稿
Route::post('/post/media/upload', [MediaController::class, 'uploadMedia']);
// メディア削除
Route::post('/post/media/delete', [MediaController::class, 'deleteMedia']);

// メール
// メール送信
Route::post('/email/post', [MailController::class, 'postEmail']);

Route::get('/laravel', fn() => view('welcome'));

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');