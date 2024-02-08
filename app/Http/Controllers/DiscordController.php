<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
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
                    'webhook' => $request->webhook,
                ]);
            } else {
                DiscordMedia::create([
                    'auto_mode' => $request->auto_mode,
                    'notifical_link' => $request->notifical_link,
                    'paste_image' => $request->paste_image,
                    'content' => $request->content,
                    'webhook' => $request->webhook,
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
                    'webhook' => $request->webhook,
                ]);
            } else {
                DiscordPost::create([
                    'auto_mode' => $request->auto_mode,
                    'notifical_link' => $request->notifical_link,
                    'content' => $request->content,
                    'webhook' => $request->webhook,
                ]);
            }
    
            return response()->json([ 'responseData' => true ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // PostWebhook
    public function PostWebhook(Request $request)
    {
        try {
            $DiscordPostData = DiscordPost::first();
            $setting = [
                'url' => null,
            ];
            
            if(!$DiscordPostData->auto_mode) {
                return response()->json([ 'responseData' => false ], 200);
            }
            if($DiscordPostData->notifical_link) {
                $setting['url'] = $request->url;
            }
            
            $data = [
                'username' => 'TPS™', 
                'content' => '新しい投稿が公開されました!',
                'embeds' => [
                    [
                        'title' => $setting['url'],
                        'description' => $DiscordPostData->content,
                        'url' => $setting['url'],
                        'timestamp' => Date::now(),
                        'color' => 5620992,
                        'footer' => [
                            // © 2021-XXXX TPS By CHACCHAN SYSTEM
                            'text' => '©' . ' 2021-' . Date::now()->format('Y') . ' ' . config('app.name') . ' By CHACCHAN SYSTEM'
                        ],
                        'thumbnail' => [
                            'url' => asset('images/components/IconImage.png')
                        ]
                    ],
                ]
            ];
            $options = [
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: application/json',
                    'content' => json_encode($data)
                ]
            ];

            file_get_contents($DiscordPostData->webhook, false, stream_context_create($options));

            return response()->json([ 'responseData' => true ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }

    // MediaWebhook
    public function MediaWebhook(Request $request)
    {
        try {
            $DiscordMediaData = DiscordMedia::first();
            $setting = [
                'url' => null,
                'image' => null,
            ];

            if(!$DiscordMediaData->auto_mode) {
                return response()->json([ 'responseData' => false ], 200);
            }
            if($DiscordMediaData->notifical_link) {
                $setting['url'] = $request->url;
            }
            if($DiscordMediaData->paste_image) {
                $setting['image'] = $request->image;
            }
            
            $data = [
                'username' => 'TPS™', 
                'content' => '画像が投稿されました!',
                'embeds' => [
                    [
                        'title' => $setting['url'],
                        'description' => $DiscordMediaData->content,
                        'url' => $setting['url'],
                        'timestamp' => Date::now(),
                        'color' => 5620992,
                        'footer' => [
                            // © 2021-XXXX TPS By CHACCHAN SYSTEM
                            'text' => '©' . ' 2021-' . Date::now()->format('Y') . ' ' . config('app.name') . ' By CHACCHAN SYSTEM'
                        ],
                        'image' => [
                            'url' => $setting['image']
                        ],
                        'thumbnail' => [
                            'url' => 'https://picsum.photos/480/480' //asset('images/components/IconImage.png')
                        ]
                    ],
                ]
            ];
            $options = [
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: application/json',
                    'content' => json_encode($data)
                ]
            ];

            file_get_contents($DiscordMediaData->webhook, false, stream_context_create($options));

            return response()->json([ 'responseData' => true ], 200);
        } catch(\Exception $e) {
            return response()->json([ 'responseData' => false ], 500);
        }
    }
}
