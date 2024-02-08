<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Media;
use App\Models\MediaGroup;

class MediaController extends Controller
{
    // メディアグループ作成
    public function makeNewMediaGroup(Request $request)
    {
        try {
            if($request->image) {
                do {
                    $image_path = Str::random(10) . '.png';
                } while(MediaGroup::where('image', $image_path)->exists());
    
                $image = base64_decode(preg_replace('/^data:image.*base64,/', '', str_replace(' ', '+', $request->image)));
                Storage::put('public/media/groupImage/' . $image_path, $image);
            } else {
                $image_path = null;
            }
            MediaGroup::create([
                'name' => $request->name,
                'image' => $image_path,
            ]);

            return response()->json([ 'responseData' => true ], 200);
        } catch (\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // メディアグループ更新
    public function updateMediaGroup(Request $request)
    {
        try {
            $MediaGroup = MediaGroup::where('id', $request->id)->first();
            if($request->image) {
                if($MediaGroup->image) {
                    Storage::disk('public')->delete('media/groupImage/' . $MediaGroup->image);
                }
                do {
                    $image_path = Str::random(10) . '.png';
                } while(MediaGroup::where('image', $image_path)->exists());
    
                $image = base64_decode(preg_replace('/^data:image.*base64,/', '', str_replace(' ', '+', $request->image)));
                Storage::put('public/media/groupImage/' . $image_path, $image);
                MediaGroup::where('id', $request->id)->update([
                    'image' => $image_path,
                ]);
            } else {
                MediaGroup::where('id', $request->id)->update([
                    'name' => $request->name,
                ]);
            }

            return response()->json([ 'responseData' => MediaGroup::where('id', $request->id)->first() ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // メディアグループ削除
    public function deleteMediaGroup(Request $request)
    {
        try {
            $Medias = Media::where('media_group_id', $request->id)->get();
            $MediaGroup = MediaGroup::where('id', $request->id)->first();
            foreach($Medias as $Media)
            {
                Storage::disk('public')->delete('media/Image/' . $Media->image);
            }
            if($MediaGroup->image)
            {
                Storage::disk('public')->delete('media/groupImage/' . $MediaGroup->image);
            }
            Media::where('media_group_id', $request->id)->delete();
            MediaGroup::where('id', $request->id)->delete();

            return response()->json([ 'responseData' => true ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
        
    }

    // メディア投稿
    public function uploadMedia(Request $request)
    {
        try {
            do {
                $image_path = Str::random(10) . '.png';
            } while(Media::where('image', $image_path)->exists());

            $image = base64_decode(preg_replace('/^data:image.*base64,/', '', str_replace(' ', '+', $request->image)));
            Storage::put('public/media/Image/' . $image_path, $image);
            Media::create([
                'media_group_id' => $request->media_group_id,
                'user_id' => Auth::id(),
                'image' => $image_path,
                'name' => $request->name,
            ]);

            return response()->json([ 'responseData' => $image_path ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        } 
    }

    // メディア削除
    public function deleteMedia(Request $request)
    {
        try {
            $Media = Media::where('id', $request->id)->first();
            Storage::disk('public')->delete('media/Image/' . $Media->image);
            Media::where('id', $request->id)->delete();

            return response()->json([ 'responseData' => true ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }
}