<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PickUp;

class PickUpController extends Controller
{
    protected $MaxLength = 30;

    // ピックアップ条件取得
    public function getPickUp(Request $request)
    {
        try {
            if($request->id) {
                $PickUps = PickUp::where('id', '<', $request->id)->orderBy('created_at', 'desc')->take($request->getCount)->get();
            } else {
                $PickUps = PickUp::orderBy('created_at', 'desc')->take($request->getCount)->get();
            }

            return response()->json([ 'responseData' => $PickUps ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // ピックアップ作成
    public function makePickUp(Request $request)
    {
        try {
            if(PickUp::count() == $this->MaxLength) {
                $OldestID = PickUp::orderBy('created_at', 'asc')->first()->id;
                PickUp::where('id', $OldestID)->delete();
            }
            PickUp::create([
                'title' => $request->title,
                'content' => $request->content
            ]);

            return response()->json([ 'responseData' => true ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // ピックアップ削除
    public function deletePickUp(Request $request)
    {
        try {
            PickUp::where('id', $request->id)->delete();

            return response()->json([ 'responseData' => true ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }
}
