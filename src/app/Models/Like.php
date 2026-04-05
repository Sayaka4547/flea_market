<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
    ];

    // いいねされた商品とのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // いいねしたユーザーとのリレーション
    public function user()
    {
        return $this->this->belongsTo(User::class);
    }

}
