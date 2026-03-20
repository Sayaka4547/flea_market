@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
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
<div class="mypage">
  <div class="profile-info">
    <div class="profile-info__left">
      <div class="profile-info__image">
        <!-- ユーザーのプロフィール画像がある場合は表示、ない場合はデフォルトのグレー円 -->
        @if(Auth::user()->profile_image)
          <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="プロフィール画像">
        @else
          <div class="profile-info__image-default"></div>
        @endif
      </div>
      <h2 class="profile-info__name">{{ Auth::user()->name }}</h2>
    </div>
    <div class="profile-info__right">
      <a href="/profile" class="profile-info__edit-btn">プロフィールを編集</a>
    </div>
  </div>

  <div class="mypage-tabs">
    <!-- 現在のURLやクエリパラメータでアクティブなタブを判定 -->
    <a href="/mypage?tab=sell" class="mypage-tabs__tab {{ request('tab', 'sell') === 'sell' ? 'mypage-tabs__tab--active' : '' }}">出品した商品</a>
    <a href="/mypage?tab=buy" class="mypage-tabs__tab {{ request('tab') === 'buy' ? 'mypage-tabs__tab--active' : '' }}">購入した商品</a>
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