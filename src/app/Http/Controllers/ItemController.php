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
        $search = $request->get('search', '');

        if($tab === 'mylist'){
            if($user){
                // マイリスト：自分がいいねした商品
                $items = $user->likes()->with('item')->get()->pluck('item');
                if ($search) {
                $items = $items->filter(fn($item) => str_contains($item->name, $search));
            }
            } else {
                // 未認証の場合は空を返す
                $items = collect();
            }
        } else {
        // おすすめ：自分以外の全商品
        $query = $user
            ? Item::where('user_id', '!=', Auth::id())
            : Item::query();

        // 検索キーワードがある場合は絞り込む
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
            }
        $items = $query->get();
        }

        return view('index', compact('items', 'tab', 'search'));
    }

    public function show($item_id)
    {
        // 商品情報を取得
        $item = Item::with(['categories', 'comments.user.profile', 'likes'])->findOrFail($item_id);

        return view('item.detail', compact('item'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'bland'       => 'nullable|string|max:255',
            'price'       => 'required|integer|min:0',
            'condition'   => 'required|string',
            'description' => 'required|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories'  => 'required|array',
        ]);

        $item = Item::create([
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'bland'       => $request->bland,
            'price'       => $request->price,
            'condition'   => $request->condition,
            'description' => $request->description,
            'image'       => $request->image->store('items', 'public'),
            'status'      => 'on_sale',
        ]);

        $item->categories()->attach($request->categories);

        return redirect('/')->with('success', '出品しました。');
    }


}
