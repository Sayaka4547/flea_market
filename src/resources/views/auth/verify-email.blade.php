@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
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
<div class="verify-email">
    <div class="verify-email__box">
        <p class="verify-email__message">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="verify-email__btn">認証はこちらから</button>
        </form>

        @if (session('status') === 'verification-link-sent')
            <p class="verify-email__resent">認証メールを再送しました。</p>
        @endif

        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="verify-email__resend-link">認証メールを再送する</button>
        </form>
    </div>
</div>
@endsection
