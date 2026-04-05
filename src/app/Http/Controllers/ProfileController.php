<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * プロフィール設定画面を表示する
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile(); // プロフィールがない場合は空のインスタンスを渡す

        return view('profile.edit', compact('user', 'profile'));
    }

    /**
     * プロフィールを更新する
     */
    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        // 1. バリデーション（後でFormRequestに切り替えることも可能）
        $request->validate([
            'name'          => 'required|string|max:255',
            'postal_code'   => 'required|string|max:8',
            'address'       => 'required|string|max:255',
            'building'      => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. ユーザー名の更新（usersテーブル）
        $user->name = $request->name;
        $user->save();

        // 3. プロフィール画像のアップロード処理
        $imagePath = $user->profile ? $user->profile->profile_image : null;
        if ($request->hasFile('profile_image')) {
            // 古い画像があれば削除
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            // 新しい画像を保存
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
        }

        // 4. プロフィール情報の更新または作成（profilesテーブル）
        Profile::updateOrCreate(
            ['user_id' => $user->id], // 検索条件
            [
                'postal_code'   => $request->postal_code,
                'address'       => $request->address,
                'building'      => $request->building,
                'profile_image' => $imagePath,
            ] // 更新・作成するデータ
        );

        return redirect('/mypage')->with('success', 'プロフィールを更新しました。');
    }
}
