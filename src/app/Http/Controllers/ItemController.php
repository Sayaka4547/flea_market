<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * 商品一覧を表示する
     */
    public function index()
    {
        $items = Item::where('user_id', '!=', Auth::id())->get();

        return view('index', compact('items'));
    }

    public function show($item_id)
    {
        // 商品情報を取得
        $item = Item::with(['categories', 'comments.user.profile', 'likes'])->findOrFail($item_id);

        return view('item.detail', compact('item'));
    }

}
