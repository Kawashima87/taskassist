<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Favorite;
use App\Models\User;

class FavoriteController extends Controller
{
    public function store(Post $post)
    {
        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
        ]);

        return back()->with('success', 'お気に入りに追加しました');
    }

    public function destroy(Post $post)
    {
        Favorite::where('user_id', auth()->id())
            ->where('post_id', $post->id)
            ->delete();

        return back()->with('success', 'お気に入りを解除しました');
    }
}
