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

@endsection