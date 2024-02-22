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
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\ViewCountController;
use App\Http\Controllers\UserBanController;
use App\Models\User;
use App\Models\PickUp;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Media;
use App\Models\MediaGroup;
use App\Models\DiscordMedia;
use App\Models\DiscordPost;
use App\Models\DiscordContact;
use App\Models\ViewCount;
use App\Models\UserBanList;
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
// メール認証(登録用)
Route::post('/auth/register', [UserController::class, 'emailAuther']);
// メール認証(確認用)
Route::post('/auth/mailcheck', [UserController::class, 'emailAutherCheck']);
// 新規登録
Route::post('/auth/register/create', [UserController::class, 'register']);
// アバター変更
Route::post('/auth/change/avatar', [UserController::class, 'changeAvatar']);
// メールアドレス変更
Route::post('/auth/change/mailaddress', [UserController::class, 'changeMailAddress']);
// パスワード変更
Route::post('/auth/change/password', [UserController::class, 'changePassword']);
// 更新
Route::post('/auth/update', [UserController::class, 'updateUser']);
// ログイン
Route::post('/auth/login', [UserController::class, 'login']);
// ログアウト
Route::get('/auth/logout', [UserController::class, 'logout']);
// ユーザ管理者を変更
Route::post('/auth/change/admin', [UserController::class, 'changeUserAdmin']);
// ユーザBANを変更
Route::post('/auth/change/ban', [UserController::class, 'changeUserBan']);
// ユーザ削除
Route::post('/auth/delete', [UserController::class, 'deleteUser']);
// ユーザ検索結果を取得
Route::post('/auth/search', [UserController::class, 'searchUser']);

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
// 公式Email取得
Route::get('/get/env/email', function() { return response()->json([ 'responseData' => config('mail.from.address') ], 200);});

// ユーザIDとユーザネームを取得
Route::get('/auth/get', function() { return response()->json([ 'responseData' => User::get(['id', 'name']) ], 200);});

// ユーザデータを取得
Route::post('/post/user/get', [UserController::class, 'getUserData']);
// ユーザデータ条件取得
Route::post('/post/user/get/focus', [UserController::class, 'getUserFocus']);
// ユーザ利用停止データ条件取得
Route::post('/post/user/ban/get/focus', [UserController::class, 'getUserBanFocus']);


// Admin Home
// ピックアップ
// ピックアップ取得
Route::get('/get/pickup', function(){ return response()->json([ 'responseData' => PickUp::latest()->get() ], 200);});
// ピックアップDB数取得
Route::get('/get/pickup/sumCount', function(){ return response()->json([ 'responseData' => PickUp::count() ], 200);});
// ピックアップ条件取得
Route::post('/post/pickup/focus', [PickUpController::class, 'getPickUp']);
// ピックアップ作成
Route::post('/post/pickup/create', [PickUpController::class, 'makePickUp']);
// ピックアップ削除
Route::post('/post/pickup/delete', [PickUpController::class, 'deletePickUp']);

// コンタクト
// コンタクト送信
Route::post('/post/contact/send', [ContactController::class, 'sendContact']);
// コンタクト受付確認送信
Route::post('/post/contact/reception', [ContactController::class, 'receptionContact']);

// Discord
// ポスト設定の取得
Route::get('/get/discord/post', [DiscordController::class, 'getPost']);
// メディア設定の取得
Route::get('/get/discord/media', [DiscordController::class, 'getMedia']);
// コンタクト設定の取得
Route::get('/get/discord/contact', [DiscordController::class, 'getContact']);
// ポスト設定の更新
Route::post('/post/discord/post', [DiscordController::class, 'updatePost']);
// メディア設定の更新
Route::post('/post/discord/media', [DiscordController::class, 'updateMedia']);
// コンタクト設定の更新
Route::post('/post/discord/contact', [DiscordController::class, 'updateContact']);
// ポストWebhook
Route::post('/post/discord/post/webhook', [DiscordController::class, 'PostWebhook']);
// メディアWebhook
Route::post('/post/discord/media/webhook', [DiscordController::class, 'MediaWebhook']);
// コンタクトWebhook
Route::post('/post/discord/contact/webhook', [DiscordController::class, 'ContactWebhook']);
// Discordに投稿
Route::post('/post/discord/send', [DiscordController::class, 'postDiscord']);

// 閲覧カウント
// 閲覧の開始
Route::post('/post/view/count/start', [ViewCountController::class, 'addViewCount']);
// 閲覧状況の取得
Route::get('/get/view/count', function() { return response()->json([ 'responseData' => ViewCount::latest()->first() ], 200); });

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
Route::get('/get/mediagroup', function() { return response()->json([ 'responseData' => MediaGroup::latest()->get() ], 200);});
// メディア取得
Route::get('/get/media', function() { return response()->json([ 'responseData' => Media::latest()->get() ], 200);});
// メディア条件取得
Route::get('/get/media/focus', function() { return response()->json([ 'responseData' => Media::orderBy('created_at', 'desc')->take(10)->get() ], 200);});
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