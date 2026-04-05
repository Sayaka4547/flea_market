<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'user_id',
        'payment_id',
        'postal_code',
        'address',
        'building',
    ];

    // 購入した商品とのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // 購入したユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 支払い方法とのリレーション
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

}
