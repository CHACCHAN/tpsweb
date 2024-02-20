<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\DiscordController;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\ContactEmail;
use App\Mail\ContactReceptionEmail;
use App\Mail\ContactReceptionAdminEmail;

class ContactController extends Controller
{
    // 問い合わせの回答処理
    public function sendContact(Request $request)
    {
        try {
            Mail::send(new ContactEmail($request->subject, $request->content, $request->email));
            
            return response()->json([ 'responseData' => true ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // 問い合わせの受付確認処理
    public function receptionContact(Request $request)
    {
        try {
            // 受付確認メール送信
            Mail::send(new ContactReceptionEmail($request->first_name, $request->last_name, $request->email, $request->subject, $request->content));

            // 管理者送信
            foreach(User::where('administrator', true)->get() as $user) {
                Mail::send(new ContactReceptionAdminEmail($request->first_name, $request->last_name, $user->email, $request->subject, $request->content));
            }

            return response()->json([ 'responseData' => true ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }
}
