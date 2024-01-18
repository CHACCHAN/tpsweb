<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostImage;
use App\Models\PostImageTemp;
use Exception;

class QuillController extends Controller
{
    // 画像の一時保存
    public function saveImageTemp(Request $request)
    {
        // $validated = $request->validate([
        //     'image' => 'image|mimes:png/gif/jfif/pjpeg/jpeg/pjp/jpg/bmp/ico|max:15360',
        // ]);
        $image_path = 'Temp-' . Date::now()->format('Y-m-d-H-i-s') . '.png';
        $image = base64_decode(preg_replace('/^data:image.*base64,/', '', str_replace(' ', '+', $request->image)));
        Storage::put('public/postImageTemp/' . $image_path, $image);
        PostImageTemp::create([
            'image_temp' => $image_path,
        ]);
        
        return response()->json([
            'image' => 'storage/postImageTemp/' . $image_path,
        ], 200);
        // try {
            
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'responseData' => $e
        //     ], 500);
        // }
    }

    // 画像の保存
    public function saveImage(Request $request)
    {

    }

    // コンテンツの保存
    public function saveContent(Request $request)
    {

    }
}
