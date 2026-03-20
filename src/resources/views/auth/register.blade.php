@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="content">
    <h1 class="title">会員登録</h1>
    <div class="form__wrap">
        <form class="register-form" action="/register" method="post" novalidate>
        @csrf
            <div class="resister-form__label">ユーザー名</div>
                <input type="text" name="name" value="{{ old('name') }}" />
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            <div class="resister-form__label">メールアドレス</div>
            <input type="email" name="email" value="{{ old('email') }}" />
                <div class="form__error">
                    @error('email')
                    {{ $message }}
                    @enderror
                </div>
            <div class="resister-form__label">パスワード</div>
            <input type="password"  name="password" />
            <div class="resister-form__label">確認用パスワード</div>
            <input type="password"  name="password_confirmation" />
                <div class="form__error">
                    @error('password')
                    {{ $password }}
                    @enderror
                </div>
            <div>
                <button type="submit">登録する</button>
            </div>
        </form>
    </div>
    <a class="login" href="/login">ログインはこちら</a>
</div>
@endsection