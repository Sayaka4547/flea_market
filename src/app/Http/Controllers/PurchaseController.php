<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

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
        $user = User::find(Auth::id());
        $user->load('profile');

        // 住所が未登録の場合は購入不可
        if (!$user->profile || !$user->profile->postal_code) {
            return redirect()->back()->withErrors(['address' => '配送先住所を登録してください。']);
        }

        // 支払い方法が未選択の場合も購入不可
        if (!$request->payment_id) {
            return redirect()->back()->withErrors(['payment' => '支払い方法を選択してください。']);
        }

        $item    = Item::findOrFail($item_id);
        $payment = Payment::findOrFail($request->payment_id);

        // 支払い方法名を取得（例：「カード支払い」「コンビニ支払い」）
        $paymentName = strtolower($payment->payment);

        // カード or コンビニの場合はStripe決済画面へ
        if (str_contains($paymentName, 'カード') || str_contains($paymentName, 'コンビニ')) {

            Stripe::setApiKey(config('services.stripe.secret'));

            // Stripeの支払い方法を判定
            $paymentMethodTypes = ['card']; // デフォルトはカード
            if (str_contains($paymentName, 'コンビニ')) {
                $paymentMethodTypes = ['konbini'];
            }

            $session = Session::create([
                'payment_method_types' => $paymentMethodTypes,
                'line_items'           => [[
                    'price_data' => [
                        'currency'     => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount'  => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode'        => 'payment',
                'success_url' => route('purchase.success', ['item_id' => $item_id, 'payment_id' => $request->payment_id]),
                'cancel_url'  => route('purchase.index', ['item_id' => $item_id]),
            ]);

            return redirect($session->url);
        }

        // Stripe以外の支払い方法の場合はそのまま購入処理
        $this->completePurchase($item_id, $request->payment_id, $user);

        return redirect('/')->with('success', '購入が完了しました。');
    }

    // Stripe決済成功後のコールバック
    public function success(Request $request)
    {
        $item_id    = $request->item_id;
        $payment_id = $request->payment_id;
        $user       = User::find(Auth::id());
        $user->load('profile');

        $this->completePurchase($item_id, $payment_id, $user);

        return redirect('/')->with('success', '購入が完了しました。');
    }

    // 購入情報の保存と商品ステータス更新
    private function completePurchase($item_id, $payment_id, $user)
    {
        Purchase::create([
            'item_id'     => $item_id,
            'user_id'     => Auth::id(),
            'payment_id'  => $payment_id,
            'postal_code' => $user->profile->postal_code,
            'address'     => $user->profile->address,
            'building'    => $user->profile->building,
        ]);

        Item::where('id', $item_id)->update(['status' => \App\Models\Item::STATUS_SOLD_OUT]);
    }
}
