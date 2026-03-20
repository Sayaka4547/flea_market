@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="content">
    <h1 class="title">ログイン</h1>
    <div class="form__wrap">
        <form class="login-form" action="/login" method="post" novalidate>
        @csrf
            <div class="login-form__label">メールアドレス</div>
            <input type="email" name="email" value="{{ old('email') }}" />
                <div class="form__error">
                    @error('email')
                    {{ $message }}
                    @enderror
                </div>
            <div class="login-form__label">パスワード</div>
            <input type="password"  name="password" />
                    @error('password')
                    {{ $password }}
                    @enderror
            <div>
                <button type="submit">ログインする</button>
            </div>
        </form>
    </div>
    <a class="register" href="/register">会員登録はこちら</a>
</div>
@endsection