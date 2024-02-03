<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostImage;
use Exception;

class QuillController extends Controller
{
    // 画像の削除
    public function removeImageTemp(Request $request)
    {
        try {
            $image = PostImage::where('id', $request->id)->first();
            Storage::disk('public')->delete('postImage/' . $image->image);
            PostImage::where('id', $request->id)->delete();

            return response()->json([ 'responseData' => true ], 200);
        } catch (\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // 画像の保存
    public function saveImage(Request $request)
    {
        try {
            do {
                $image_path = Str::random(10) . '.png';
            } while(PostImage::where('image', $image_path)->exists());
            
            $image = base64_decode(preg_replace('/^data:image.*base64,/', '', str_replace(' ', '+', $request->image)));
            Storage::put('public/postImage/' . $image_path, $image);
            PostImage::create([
                'post_id' => $request->post_id,
                'image' => $image_path,
            ]);
            
            return response()->json([
                'id' => PostImage::where('image', $image_path)->first()->id,
                'image' => '/tps-site/storage/postImage/' . $image_path
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['image' => false], 500);
        }
    }

    // コンテンツの保存
    public function saveContent(Request $request)
    {
        try {
            Post::where('id', $request->id)->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);
            return response()->json([ 'responseData' => true ], 200);
        } catch (\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }
}
