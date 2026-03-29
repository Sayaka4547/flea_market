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
  @foreach($items as $item)
  <div class="item-card">
    <div class="item-card__image">
    @if($item->image)
      <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
    @else
      <span class="item-card__image-text">商品画像</span>
    @endif
    </div>
    <p class="item-card__name">{{ $item->name }}</p>
  </div>
  @endforeach
  </div>

</div>
@endsection