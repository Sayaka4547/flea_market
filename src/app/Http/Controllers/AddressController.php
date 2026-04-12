<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 住所変更フォームを表示
     */
    public function edit(Request $request)
    {
        $user = User::find(Auth::id());
        $profile = $user->profile;
        $item_id = $request->item_id;

        return view('address', compact('profile', 'item_id'));
    }

    /**
     * 住所を更新する
     */
    public function update(Request $request)
    {
        $request->validate([
            'postal_code' => ['nullable', 'string', 'max:8'],
            'address'     => ['nullable', 'string', 'max:255'],
            'building'    => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::find(Auth::id());

        if ($user->profile) {
            $user->profile->update([
                'postal_code' => $request->postal_code,
                'address'     => $request->address,
                'building'    => $request->building,
            ]);
        } else {
            $user->profile()->create([
                'postal_code' => $request->postal_code,
                'address'     => $request->address,
                'building'    => $request->building,
            ]);
        }

        // 購入ページへリダイレクト
            if ($request->item_id) {
                return redirect()->route('purchase.index', ['item_id' => $request->item_id])
            ->with('success', '住所を更新しました。');
            }
            return redirect('address/edit')->with('success', '住所を更新しました。');
    }
}
