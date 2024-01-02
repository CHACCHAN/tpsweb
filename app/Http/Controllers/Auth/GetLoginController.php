<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetLoginController extends Controller
{
    public function getLoginAPI()
    {
        return response()->json([
            'ResponseData' => Auth::check(),
        ], 200);
    }
}
