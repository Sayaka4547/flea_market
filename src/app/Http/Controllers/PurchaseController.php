<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index($item_id)
    {
    $item     = Item::findOrFail($item_id);
    $payments = Payment::all();
    $user     = User::find(Auth::id());
    $user->load('profile');

    return view('purchase', compact('item', 'payments', 'user'));
    }

    public function store(Request $request, $item_id)
    {
    $user     = User::find(Auth::id());
    $user->load('profile');

    // 購入情報を保存
    Purchase::create([
        'item_id'     => $item_id,
        'user_id'     => Auth::id(),
        'payment_id'  => $request->payment_id,
        'postal_code' => $user->profile->postal_code,
        'address'     => $user->profile->address,
        'building'    => $user->profile->building,
    ]);

    return redirect('/')->with('success', '購入が完了しました。');
    }
}
