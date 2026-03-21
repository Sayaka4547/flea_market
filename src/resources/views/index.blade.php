@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
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
<div class="content">

  <div class="tabs">
    <!-- 現在のURLやクエリパラメータでアクティブなタブを判定 -->
    <a href="/mypage?tab=sell" class="tabs__tab {{ request('tab', 'sell') === 'sell' ? 'mypage-tabs__tab--active' : '' }}">おすすめ</a>
    <a href="/mypage?tab=buy" class="tabs__tab {{ request('tab') === 'buy' ? 'mypage-tabs__tab--active' : '' }}">マイリスト</a>
  </div>

  <div class="item-list">
    <!-- 商品リストのループ (例としてダミーデータを表示する想定) -->
    @for($i = 0; $i < 8; $i++)
    <div class="item-card">
      <div class="item-card__image">
        <span class="item-card__image-text">商品画像</span>
      </div>
      <p class="item-card__name">商品名</p>
    </div>
    @endfor
  </div>

</div>
@endsection