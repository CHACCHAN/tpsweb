<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetEnvController extends Controller
{
    public function getEnvAPI(Request $request)
    {
        $ResponseData = NULL;

        if($request->set == 'APP_NAME')
        {
            $ResponseData = config('app.name');
        }

        return response()->json([
            'ResponseData' => $ResponseData,
        ], 200);
    }
}
