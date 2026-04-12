<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Purchase;


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

    const STATUS_ON_SALE = 'on_sale';
    const STATUS_SOLD_OUT = 'sold_out';

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
    // 購入情報とのリレーション
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }
}
