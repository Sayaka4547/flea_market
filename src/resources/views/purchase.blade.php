@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('search')
    <x-search-box />
@endsection

@section('navigation')
    <nav>
        <x-nav />
    </nav>
@endsection

@section('content')
<div class="purchase-page">
    <div class="purchase-page__left">
        <!-- 商品情報 -->
        <div class="item-summary">
            <div class="item-summary__image">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                @else
                    <span class="item-summary__image-text">商品画像</span>
                @endif
            </div>
            <div class="item-summary__details">
                <h2 class="item-summary__name">{{ $item->name }}</h2>
                <p class="item-summary__price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>

        <!-- 支払い方法選択 -->
        <div class="payment-method">
            <h3 class="section-title">支払い方法</h3>
            <div class="form__select">
                <select name="payment_id" id="payment_select" form="purchase-form">
                    <option value="" disabled selected>選択してください</option>
                    @foreach($payments as $payment)
                        <option value="{{ $payment->id }}">{{ $payment->payment }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- 配送先 -->
        <div class="shipping-address">
            <div class="shipping-address__header">
                <h3 class="section-title">配送先</h3>
                <a href="{{ route('address.edit', ['item_id' => $item->id])}}" class="shipping-address__change-btn">変更する</a>
            </div>
            <div class="shipping-address__info">
                @if($user->profile && $user->profile->postal_code)
                    <p>〒 {{ $user->profile->postal_code }}</p>
                    <p>{{ $user->profile->address }} {{ $user->profile->building }}</p>
                @else
                    <p>配送先が登録されていません。</p>
                @endif
            </div>
        </div>
    </div>

    <div class="purchase-page__right">
        <!-- 注文確認と購入ボタン -->
        <div class="order-summary">
            <table class="order-summary__table">
                <tr>
                    <th>商品代金</th>
                    <td>¥{{ number_format($item->price) }}</td>
                </tr>
                <tr>
                    <th>支払い方法</th>
                    <td id="display_payment_method">選択してください</td>
                </tr>
            </table>
        </div>

        <form action="/purchase/{{ $item->id }}" method="POST" id="purchase-form">
            @csrf
            <!-- JavaScriptで選択された支払い方法を送信するために使用 -->
            <input type="hidden" name="payment_id" id="hidden_payment_id" value="">
            <button type="submit" class="purchase-btn">購入する</button>
        </form>
    </div>
</div>

@endsection

@section('js')
<script>
    document.getElementById('payment_select').addEventListener('change', function() {
        // 選択された支払い方法のテキストを取得して表示を更新
        var selectedText = this.options[this.selectedIndex].text;
        document.getElementById('display_payment_method').innerText = selectedText;
        
        // hidden inputの値を更新
        document.getElementById('hidden_payment_id').value = this.value;
    });
</script>
@endsection
