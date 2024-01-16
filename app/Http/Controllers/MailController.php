<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\postEmail;
use App\Models\User;

class MailController extends Controller
{
    public function postEmail(Request $request)
    {
        $users = User::get();

        foreach($users as $user)
        {
            Mail::send(new postEmail($request->subject, $request->content, $user->email));
        }
        
        $request->session()->regenerate();

        return response()->json([
            'csrf' => $request->session()->token()
        ], 200);
    }
}
