<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\TokenEmail;
use App\Models\User;
use App\Models\UserBanList;
use App\Models\Media;
use App\Models\MediaGroup;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostImage;
use App\Mail\PostEmail;
use Carbon\Carbon;

class UserController extends Controller
{
    // ユーザ取得(セキュリティ付)
    public function getUserData(Request $request)
    {
        try {
            if($request->link == '/tps-site/admin#view=ユーザ') {
                return response()->json([ 
                    'responseData' => User::get(),
                    'responseTable' => $request
                ], 200);
            } else {
                return response()->json([ 'responseData' => false ], 400);
            }
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // ユーザ条件取得
    public function getUserFocus(Request $request)
    {
        try {
            if($request->link == '/tps-site/admin#view=ユーザ') {
                return response()->json([ 'responseData' => User::where('id', $request->id)->first() ], 200);
            } else {
                return response()->json([ 'responseData' => false ], 400);
            }
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // ユーザ利用停止条件取得
    public function getUserBanFocus(Request $request)
    {
        try {
            if($request->link == '/tps-site/admin#view=ユーザ') {
                return response()->json([ 'responseData' => UserBanList::where('user_id', $request->id)->first() ], 200);
            } else {
                return response()->json([ 'responseData' => false ], 400);
            }
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // ユーザ権限変更
    public function changeUserAdmin(Request $request)
    {
        try {
            if($request->link == '/tps-site/admin#view=ユーザ') {
                User::where('id', $request->id)->update([
                    'administrator' => $request->administrator
                ]);

                return response()->json([ 'responseData' => true ], 200);
            } else {
                return response()->json([ 'responseData' => false ], 400);
            }
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // ユーザ停止変更
    public function changeUserBan(Request $request)
    {
        try {
            if($request->link == '/tps-site/admin#view=ユーザ') {
                if($request->ban) {
                    $start_date = Carbon::parse($request->start_date);
                    $last_date = Carbon::parse($request->last_date);

                    if($start_date->lt($last_date)) {
                        UserBanList::create([
                            'user_id' => $request->id,
                            'start_date' => $request->start_date,
                            'last_date' => $request->last_date,
                            'level' => $request->level,
                        ]);
                    } else {
                        return response()->json([ 'responseData' => false ], 400);
                    }
                } else {
                    UserBanList::where('user_id', $request->id)->delete();
                }

                return response()->json([ 'responseData' => true ], 200);
            } else {
                return response()->json([ 'responseData' => false ], 400);
            }
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // 確認メール
    public function emailAuther(Request $request)
    {
        if(!User::where('email', $request->email)->exists())
        {
            Mail::send(new TokenEmail($request->subject, $request->email, $request->onetime_token));
            $emailCheck = true;
        }
        else
        {
            $emailCheck = false;
        }

        return response()->json([
            'emailCheck' => $emailCheck,
        ], 200);
    }

    // ユーザ更新
    public function updateUser(Request $request)
    {
        try {
            User::where('id', $request->id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->name,
                'email' => $request->email,
            ]);

            return response()->json([ 'responseData' => true ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // ユーザ削除
    public function deleteUser(Request $request)
    {
        try {
            // 依存データ削除
            foreach(Post::where('user_id', $request->id)->get() as $Post) {
                foreach(PostImage::where('post_id', $Post->id)->get() as $PostImage) {
                    Storage::disk('public')->delete('postImage/' . $PostImage->image);
                    PostImage::where('id', $PostImage->id)->delete();
                }
                Post::where('id', $Post->id)->delete();
            }
            foreach(Media::where('user_id', $request->id)->get() as $Media) {
                Storage::disk('public')->delete('media/Image/' . $Media->image);
                Media::where('id', $Media->id)->delete();
            }

            $User = User::where('id', $request->id)->first();
            if($User->avatar) {
                Storage::disk('public')->delete('user/avatar/' . $User->image);
            }
            User::where('id', $request->id)->delete();

            $subject = '退会完了のお知らせ';
            $content = 
                        '
                            退会手続きが完了いたしました。<br>
                            またのご利用をお待ちしております。<br>
                            <br>
                            TPS管理部
                        ';
            Mail::send(new postEmail($subject, $content, $User->email));

            return response()->json([ 'responseData' => true ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // 新規登録
    public function register(Request $request)
    {
        if($request->Token == $request->InputToken)
        {
            $user = User::query()->create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name'  => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            
            $check = true;

            Auth::login($user);
        }
        else
        {
            $check = false;
        }

        return response()->json([
            'check' => $check,
        ], 200);
    }

    // ユーザ検索
    public function searchUser(Request $request)
    {
        try {
            $users = User::query();

            if($request->name) {
                $users->where('name', 'like', '%' . $request->name . '%');
            }
            if($request->email) {
                $users->where('email', 'like', '%' . $request->email . '%');
            }

            return response()->json([ 'responseData' => $users->get() ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // ログイン
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $responseData = true;
        $user_ban_list = null;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $check = true;
            $current_time = Carbon::now()->format('Y-m-d');

            if(UserBanList::where('user_id', Auth::id())->exists()) {
                $UserBanList = UserBanList::where('user_id', Auth::id())->first();
                if(Carbon::parse($UserBanList->last_date)->lt($current_time)) {
                    UserBanList::where('user_id', Auth::id())->delete();
                } else {
                    Auth::logout();
                    $responseData = false;
                    $user_ban_list = $UserBanList;
                }
            }
        }
        else
        {
            $check = false;
        }

        return response()->json([
            'check' => $check,
            'csrf' => $request->session()->token(),
            'responseData' => $responseData,
            'user_ban_list' => $user_ban_list,
        ], 200);
    }

    // ログアウト
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
