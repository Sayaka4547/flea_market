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
    <a href="/?tab=recommend" class="tabs__tab {{ $tab === 'recommend' ? 'tabs__tab--active' : '' }}">おすすめ</a>
    <a href="/?tab=mylist" class="tabs__tab {{ $tab === 'mylist' ? 'tabs__tab--active' : '' }}">マイリスト</a>
  </div>

  <div class="item-list">
  @foreach($items as $item)
  <a href="/item/{{ $item->id }}" class="item-card">
    <div class="item-card__image">
    @if($item->image)
      <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
    @else
      <span class="item-card__image-text">商品画像</span>
    @endif
    @if($item->status === 'sold_out')
      <div class="item-card__sold-label">Sold</div>
    @endif
    </div>
    <p class="item-card__name">{{ $item->name }}</p>
  </a>
  @endforeach
  </div>

</div>
@endsection