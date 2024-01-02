<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TokenEmail;
use App\Models\User;

class UserController extends Controller
{
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

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $check = true;
        }
        else
        {
            $check = false;
        }

        return response()->json([
            'check' => $check,
        ], 200);
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
