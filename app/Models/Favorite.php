<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
    ];

    // お気に入りしたユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // お気に入り対象の投稿とのリレーション
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
