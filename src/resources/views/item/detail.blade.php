@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/item_detail.css') }}">
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
<div class="item-detail">
  <!-- 左側：商品画像 -->
  <div class="item-detail__image">
    @if($item->image)
      <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
    @else
      <span>商品画像</span>
    @endif
  </div>

  <!-- 右側：商品情報 -->
  <div class="item-detail__info">
    <h2 class="item-detail__name">{{ $item->name }}</h2>
    <p class="item-detail__brand">{{ $item->bland }}</p>
    <p class="item-detail__price">¥{{ number_format($item->price) }} <span class="tax">(税込)</span></p>

    <!-- いいね・コメントアイコン -->
    <div class="item-detail__actions">
      <div class="action-item">
        @auth
          <!-- ログイン済：いいねボタン（POSTフォーム） -->
          <form action="/item/{{ $item->id }}/like" method="POST" class="action-form">
            @csrf
            <button type="submit" class="action-btn">
                <img src="{{ asset('images/heartpink.png') }}" alt="いいね" width="24">
            </button>
          </form>
        @else
          <!-- 未ログイン：ログイン画面へ誘導 -->
          <a href="/login" class="action-btn">
            <img src="{{ asset('images/heart.png') }}" alt="いいね" width="24">
          </a>
        @endauth
        <span class="action-count">{{ $item->likes()->count() }}</span>
      </div>
      <div class="action-item">
        <div class="action-btn">
          <img src="{{ asset('images/speechbubble.png') }}" alt="コメント" width="24">
        </div>
        <span class="action-count">{{ $item->comments()->count() }}</span>
      </div>
    </div>

    <!-- 購入ボタン -->
    <a href="/purchase/{{ $item->id }}" class="purchase-btn">購入手続きへ</a>

    <!-- 商品説明 -->
    <div class="item-description">
      <h3>商品説明</h3>
      <p class="description-text">{!! nl2br(e($item->description)) !!}</p>
    </div>

    <!-- 商品の情報 -->
    <div class="item-info">
      <h3>商品の情報</h3>
      <div class="info-row">
        <span class="info-label">カテゴリー</span>
        <div class="category-tags">
          @foreach($item->categories as $category)
            <span class="category-tag">{{ $category->name }}</span>
          @endforeach
        </div>
      </div>
      <div class="info-row">
        <span class="info-label">商品の状態</span>
        <span class="condition-text">{{ $item->condition }}</span>
      </div>
    </div>

    <!-- コメントセクション -->
    <div class="comments-section">
      <h3>コメント ({{ $item->comments()->count() }})</h3>
      
      <div class="comments-list">
        @foreach($item->comments as $comment)
        <div class="comment">
          <div class="comment-user">
            <div class="user-icon">
              <!-- ユーザーのプロフィール画像（リレーションがある前提） -->
              @if(isset($comment->user->profile) && $comment->user->profile->profile_image)
                <img src="{{ asset('storage/' . $comment->user->profile->profile_image) }}" alt="user">
              @else
                <div class="default-icon"></div>
              @endif
            </div>
            <span class="user-name">{{ $comment->user->name }}</span>
          </div>
          <div class="comment-body">
            <p>{{ $comment->comment }}</p>
          </div>
        </div>
        @endforeach
      </div>

      <div class="comment-form">
        <h4>商品へのコメント</h4>
        @auth
        <!-- ログイン済：コメント投稿フォーム -->
        <form action="/item/{{ $item->id }}/comment" method="POST">
          @csrf
          <textarea name="comment" class="comment-textarea"></textarea>
          <button type="submit" class="comment-submit-btn">コメントを送信する</button>
        </form>
        @else
        <!-- 未ログイン：テキストエリア無効化＆ボタンでログイン画面へ -->
        <textarea class="comment-textarea" disabled placeholder="コメントを投稿するにはログインしてください"></textarea>
        <a href="/login" class="comment-submit-btn link-btn">コメントを送信する</a>
        @endauth
      </div>
    </div>
  </div>
</div>
@endsection