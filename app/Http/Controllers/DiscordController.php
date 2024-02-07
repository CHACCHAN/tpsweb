<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiscordMedia;
use App\Models\DiscordPost;

class DiscordController extends Controller
{
    // Discordに投稿
    public function postDiscord(Request $request)
    {
        return response()->json([ 'responseData' => true ], 200);
    }

    // Mediaの更新
    public function updateMedia(Request $request)
    {
        try {
            if(DiscordMedia::where('id', 1)->exists()) {
                DiscordMedia::where('id', 1)->update([
                    'auto_mode' => $request->auto_mode,
                    'notifical_link' => $request->notifical_link,
                    'paste_image' => $request->paste_image,
                    'content' => $request->content,
                ]);
            } else {
                DiscordMedia::create([
                    'auto_mode' => $request->auto_mode,
                    'notifical_link' => $request->notifical_link,
                    'paste_image' => $request->paste_image,
                    'content' => $request->content,
                ]);
            }
    
            return response()->json([ 'responseData' => true ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // Postの更新
    public function updatePost(Request $request)
    {
        try {
            if(DiscordPost::where('id', 1)->exists()) {
                DiscordPost::where('id', 1)->update([
                    'auto_mode' => $request->auto_mode,
                    'notifical_link' => $request->notifical_link,
                    'content' => $request->content,
                ]);
            } else {
                DiscordPost::create([
                    'auto_mode' => $request->auto_mode,
                    'notifical_link' => $request->notifical_link,
                    'content' => $request->content,
                ]);
            }
    
            return response()->json([ 'responseData' => true ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }
}
