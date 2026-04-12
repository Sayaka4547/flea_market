<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * 商品一覧を表示する
     */
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        $tab  = $request->get('tab', 'recommend');
        if($tab === 'mylist' && $user) {
        // マイリスト：自分がいいねした商品
        $items = $user->likes()->with('item')->get()->pluck('item');
        } else {
            // おすすめ：自分以外の全商品
        if ($user){
            $items = Item::where('user_id', '!=', Auth::id())->get();
            }else{
                $items = Item::all();
            }
        }

        return view('index', compact('items', 'tab'));
    }

    public function show($item_id)
    {
        // 商品情報を取得
        $item = Item::with(['categories', 'comments.user.profile', 'likes'])->findOrFail($item_id);

        return view('item.detail', compact('item'));
    }

}
