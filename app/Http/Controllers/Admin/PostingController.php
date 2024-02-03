<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostImage;

class PostingController extends Controller
{
    // カテゴリ
    // カテゴリの新規作成
    public function makeNewCategory(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required']
        ]);

        $PostCategory = new PostCategory;
        if(PostCategory::where('category', $request->category)->exists())
        {
            $max_number = PostCategory::where('category', 'like', $request->category . '(%)')->get()->map(function ($post) {
                preg_match('/\((\d+)\)$/', $post->category, $matches);
                return isset($matches[1]) ? (int) $matches[1] : 0;
            })->max();
            $PostCategory->category = $request->category . '(' . ($max_number + 1) . ')';
        }
        else
        {
            $PostCategory->category = $request->category;
        }
        $PostCategory->save();

        return response()->json(['responseData' => true], 200);
    }

    // カテゴリタイトルの更新
    public function updateCategoryTitle(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required']
        ]);

        if(PostCategory::where('category', $request->category)->exists())
        {
            $max_number = PostCategory::where('category', 'like', $request->category . '(%)')->get()->map(function ($post) {
                preg_match('/\((\d+)\)$/', $post->category, $matches);
                return isset($matches[1]) ? (int) $matches[1] : 0;
            })->max();
            PostCategory::where('id', $request->id)->update([
                'category' => $request->category . '(' . ($max_number + 1) . ')',
            ]);
        }
        else
        {
            PostCategory::where('id', $request->id)->update([
                'category' => $request->category,
            ]);
        }
    
        return response()->json(['responseData' => true], 200);
    }

    // カテゴリの削除
    public function deleteCategory(Request $request)
    {
        return response()->json([], 200);
    }

    // プロジェクト
    // プロジェクトの新規作成
    public function makeNewProject(Request $request)
    {
        $validated = $request->validate([
            'title' => ['string', 'required'],
            'category_id' => ['integer', 'required']
        ]);

        $Post = new Post;
        if(Post::where([['category_id', $request->category_id], ['title', $request->title]])->exists())
        {
            $max_number = Post::where([['category_id', $request->category_id], ['title', 'like', $request->title . '(%)']])->get()->map(function ($post) {
                preg_match('/\((\d+)\)$/', $post->title, $matches);
                return isset($matches[1]) ? (int) $matches[1] : 0;
            })->max();
            $Post->title = $request->title . '(' . ($max_number + 1) . ')';
        }
        else
        {
            $Post->title = $request->title;
        }
        $Post->content = NULL;
        $Post->user_id = Auth::id();
        $Post->public = false;
        $Post->category_id = $request->category_id;
        $Post->save();

        return response()->json(['responseData' => true], 200);
    }

    // プロジェクトタイトルの更新
    public function updateProjectTitle(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required']
        ]);

        $PostTemp = Post::where('id', $request->id)->first();
        if(Post::where([['category_id', $request->category_id], ['title', $request->title]])->exists())
        {
            $max_number = Post::where([['category_id', $request->category_id], ['title', 'like', $PostTemp->title . '(%)']])->get()->map(function ($post) {
                preg_match('/\((\d+)\)$/', $post->title, $matches);
                return isset($matches[1]) ? (int) $matches[1] : 0;
            })->max();
            Post::where('id', $request->id)->update([
                'title' => $request->title . '(' . ($max_number + 1) . ')',
            ]);
        }
        else
        {
            Post::where('id', $request->id)->update([
                'title' => $request->title,
            ]);
        }

        return response()->json(['responseData' => true], 200);
    }

    // プロジェクトの削除
    public function deleteProject(Request $request)
    {
        foreach(PostImage::where('post_id', $request->id)->get() as $image)
        {
            Storage::disk('public')->delete('postImage/' . $image->image);
        }
        PostImage::where('post_id', $request->id)->delete();
        Post::where('id', $request->id)->delete();

        return response()->json([ 'responseData' => true ], 200);
    }

    // プロジェクトの移動
    public function moveProject(Request $request)
    {
        $PostTemp = Post::where('id', $request->id)->first();
        if(Post::where([['category_id', $request->category_id], ['title', $PostTemp->title]])->exists())
        {
            $max_number = Post::where([['category_id', $request->category_id], ['title', 'like', $PostTemp->title . '(%)']])->get()->map(function ($post) {
                preg_match('/\((\d+)\)$/', $post->title, $matches);
                return isset($matches[1]) ? (int) $matches[1] : 0;
            })->max();
            Post::where('id', $request->id)->update([
                'title' => $PostTemp->title . '(' . ($max_number + 1) . ')',
                'category_id' => $request->category_id,
            ]);
        }
        else
        {
            Post::where('id', $request->id)->update([
                'category_id' => $request->category_id,
            ]);
        }

        return response()->json(['responseData' => true], 200);
    }

    // プロジェクトの複製
    public function copyProject(Request $request)
    {
        $PostTemp = Post::where('id', $request->id)->first();
        $Post = new Post;
        if(Post::where([['category_id', $request->category_id], ['title', $PostTemp->title]])->exists())
        {
            $max_number = Post::where([['category_id', $request->category_id], ['title', 'like', $PostTemp->title . '(%)']])->get()->map(function ($post) {
                preg_match('/\((\d+)\)$/', $post->title, $matches);
                return isset($matches[1]) ? (int) $matches[1] : 0;
            })->max();
            $Post->title = $PostTemp->title . '(' . ($max_number + 1) . ')';
        }
        else
        {
            $Post->title = $PostTemp->title;
        }
        $Post->content = $PostTemp->content;
        $Post->user_id = Auth::id();
        $Post->category_id = $request->category_id;
        $Post->save();

        return response()->json(['responseData' => true], 200);
    }

    // プロジェクトの公開設定更新
    public function updateProjectPublic(Request $request)
    {
        Post::where('id', $request->id)->update(['public' => $request->public]);
        return response()->json([ 'responseData' => true ], 200);
    }
}
