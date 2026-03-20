@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
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
<div class="item-create">
  <div class="item-create__heading">
    <h1>商品の出品</h1>
  </div>

  <form class="form" action="/items" method="post" enctype="multipart/form-data">
    @csrf

    <!-- 商品画像 -->
    <div class="form__section">
      <h3 class="form__section-title">商品画像</h3>
      <div class="image-upload-area">
        <label for="image" class="image-upload-area__label">画像を選択する</label>
        <input type="file" name="image" id="image" class="image-upload-area__input" accept="image/*">
        <div class="form__error">
          @error('image')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <!-- 商品の詳細 -->
    <div class="form__section">
      <h3 class="form__section-title form__section-title--border">商品の詳細</h3>

      <!-- カテゴリー -->
      <div class="form__group">
        <label class="form__label">カテゴリー</label>
        <div class="category-tags">
          @foreach($categories as $category)
          <label class="category-tag">
            <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="category-tag__input" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
            <span class="category-tag__text">{{ $category->name}}</span>
          </label>
          @endforeach
        </div>
        <div class="form__error">
          @error('categories')
          {{ $message }}
          @enderror
        </div>
      </div>

      <!-- 商品の状態 -->
      <div class="form__group">
        <label class="form__label">商品の状態</label>
        <div class="form__select">
          <select name="condition">
            <option value="" hidden>選択してください</option>
            <option value="良好" {{ old('condition') === '良好' ? 'selected' : '' }}>良好</option>
            <option value="目立った傷や汚れなし" {{ old('condition') === '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
            <option value="やや傷や汚れあり" {{ old('condition') === 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
            <option value="状態が悪い" {{ old('condition') === '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
          </select>
        </div>
        <div class="form__error">
          @error('condition')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <!-- 商品名と説明 -->
    <div class="form__section">
      <h3 class="form__section-title form__section-title--border">商品名と説明</h3>

      <!-- 商品名 -->
      <div class="form__group">
        <label class="form__label">商品名</label>
        <div class="form__input--text">
          <input type="text" name="name" value="{{ old('name') }}">
        </div>
        <div class="form__error">
          @error('name')
          {{ $message }}
          @enderror
        </div>
      </div>

      <!-- ブランド名 -->
      <div class="form__group">
        <label class="form__label">ブランド名</label>
        <div class="form__input--text">
          <input type="text" name="brand" value="{{ old('brand') }}">
        </div>
        <div class="form__error">
          @error('brand')
          {{ $message }}
          @enderror
        </div>
      </div>

      <!-- 商品の説明 -->
      <div class="form__group">
        <label class="form__label">商品の説明</label>
        <div class="form__input--textarea">
          <textarea name="description" rows="5">{{ old('description') }}</textarea>
        </div>
        <div class="form__error">
          @error('description')
          {{ $message }}
          @enderror
        </div>
      </div>

      <!-- 販売価格 -->
      <div class="form__group">
        <label class="form__label">販売価格</label>
        <div class="form__input--price">
          <span class="price-symbol">¥</span>
          <input type="number" name="price" value="{{ old('price') }}">
        </div>
        <div class="form__error">
          @error('price')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>

    <div class="form__button">
      <button class="form__button-submit" type="submit">出品する</button>
    </div>
  </form>
</div>
@endsection