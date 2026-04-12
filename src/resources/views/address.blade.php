@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
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
<div class="address-page">
    <h2 class="address-page__title">住所の変更</h2>

    <form action="{{ route('address.update') }}" method="POST" class="address-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="item_id" value="{{ request('item_id') }}">
        <div class="form-group">
            <label class="form-label" for="postal_code">郵便番号</label>
            <input
                type="text"
                id="postal_code"
                name="postal_code"
                class="form-input @error('postal_code') is-error @enderror"
                value="{{ old('postal_code', $profile->postal_code ?? '') }}"
                placeholder=""
            >
            @error('postal_code')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="address">住所</label>
            <input
                type="text"
                id="address"
                name="address"
                class="form-input @error('address') is-error @enderror"
                value="{{ old('address', $profile->address ?? '') }}"
                placeholder=""
            >
            @error('address')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="building">建物名</label>
            <input
                type="text"
                id="building"
                name="building"
                class="form-input @error('building') is-error @enderror"
                value="{{ old('building', $profile->building ?? '') }}"
                placeholder=""
            >
            @error('building')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-submit">
            <button type="submit" class="submit-btn">更新する</button>
        </div>
    </form>
</div>
@endsection
