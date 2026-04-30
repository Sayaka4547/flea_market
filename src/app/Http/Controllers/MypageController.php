<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;

class MypageController extends Controller
{
    public function index(Request $request)
    {
    $user = User::find(Auth::id());
    $tab  = $request->get('tab', 'sell'); // デフォルトは「出品した商品」

    if ($tab === 'buy') {
        // 購入した商品
        $items = Item::whereHas('purchase', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('purchase')->get();
    } else {
        // 自分が出品した商品
        $items = Item::where('user_id', $user->id)->with('purchase')->get();
    }

    return view('mypage', compact('items', 'tab'));
    }

}
