<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;


class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'bland',
        'price',
        'condition',
        'description',
        'image',
        'status'
    ];
    // 出品したユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // カテゴリーとのリレーション（中間テーブル）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    // コメントとのリレーション
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // いいねとのリレーション
    public function likes()
    {
    return $this->hasMany(Like::class);
    }

}
