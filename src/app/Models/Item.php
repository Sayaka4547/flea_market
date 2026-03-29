<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
