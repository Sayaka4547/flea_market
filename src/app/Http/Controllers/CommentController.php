<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * コメントを投稿する
     *
     * @param int $item_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $item_id)
    {
        // 1. バリデーション（後でFormRequestに切り替えることも可能）
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // 2. コメントの保存
        Comment::create([
            'item_id' => $item_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        // 3. 元の詳細画面へリダイレクト
        return redirect()->back()->with('success', 'コメントを投稿しました。');
    }
}
