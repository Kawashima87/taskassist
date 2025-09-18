<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'program_path',
        'arguments',
        'run_datetime',
        'enabled',
        'screenshot_path',
        'action_type',
        'popup_title',
        'popup_message',
        'ps1_path',
    ];

    //投稿者とのリレーション
    public function user() 
    {
        return $this->belongsTo(User::class);
    }
    //お気に入りとのリレーション
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    //お気に入り済みか調べる
    public function isFavoritedBy($user)
    {
        return $this->favorites()->where('user_id', $user->id)->exists();
    }
    
}
