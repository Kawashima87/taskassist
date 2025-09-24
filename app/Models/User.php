<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Favorite;
use App\Models\Post;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //お気に入りとのリレーション
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // 自分が作成した投稿
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    //お気に入り済みを一覧表示
    public function favoritePosts()
    {
        return $this->belongsToMany(Post::class, 'favorites')->withTimestamps();
    }

    //アイコン
    public function getIconUrlAttribute()
    {
        if($this->icon_path && file_exists(storage_path('app/public/'.$this->icon_path))){
            return asset('storage/'.$this->icon_path);
        }
        return asset('storage/icons/default.png');//デフォルトアイコン
    }
}
