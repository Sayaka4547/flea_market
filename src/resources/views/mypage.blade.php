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
      <a href="/mypage/profile" class="profile-info__edit-btn">プロフィールを編集</a>
    </div>
  </div>

  <div class="mypage-tabs">
    <a href="/mypage?tab=sell" class="mypage-tabs__tab {{ $tab === 'sell' ? 'mypage-tabs__tab--active' : '' }}">出品した商品</a>
    <a href="/mypage?tab=buy" class="mypage-tabs__tab {{ $tab === 'buy' ? 'mypage-tabs__tab--active' : '' }}">購入した商品</a>
  </div>

  <div class="item-list">
    @forelse($items as $item)
    <a href="/item/{{ $item->id }}" class="item-card">
      <div class="item-card__image">
        @if($item->image)
          <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
        @else
          <span class="item-card__image-text">商品画像</span>
        @endif
        {{-- 購入済みの場合はSoldラベルを表示 --}}
        @if($item->purchase)
          <div class="item-card__sold-label">Sold</div>
        @endif
      </div>
      <p class="item-card__name">{{ $item->name }}</p>
    </a>
    @empty
      <p class="item-list__empty">表示する商品がありません。</p>
    @endforelse
  </div>
  
</div>
@endsection