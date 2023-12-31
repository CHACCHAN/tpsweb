<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetEnvController extends Controller
{
    public function getEnvAPI(Request $request)
    {
        $ResponseDatas = NULL;

        if($request->set == 'APP_NAME')
        {
            $ResponseDatas = config('app.name');
        }

        return response()->json([
            'ResponseDatas' => $ResponseDatas,
        ], 200);
    }
}
