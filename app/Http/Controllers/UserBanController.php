<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserBanList;

class UserBanController extends Controller
{
    // ユーザBANを付与
    public function addUserBan(Request $request)
    {
        try {
            return response()->json([ 'responseData' => true ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }
}
