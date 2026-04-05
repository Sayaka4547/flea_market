<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * いいねを追加または削除する（トグル処理）
     *
     * @param int $item_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle($item_id)
    {
        $user_id = Auth::id();

        // 1. 既にいいねしているか確認
        $like = Like::where('item_id', $item_id)->where('user_id', $user_id)->first();

        if ($like) {
            // 2. いいねが存在する場合は削除（いいね解除）
            $like->delete();
            return redirect()->back()->with('success', 'いいねを解除しました。');
        } else {
            // 3. いいねが存在しない場合は作成（いいね追加）
            Like::create([
                'item_id' => $item_id,
                'user_id' => $user_id,
            ]);
            return redirect()->back()->with('success', 'いいねしました。');
        }
    }
}