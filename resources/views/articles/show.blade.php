@extends('layout')

@section('content')

    Nazov: {{ $article->name }}<br/>
    Obsah: {{ $article->content }}<br/>

    @if(Auth::user() && Auth::user() == $article->user)
        <a href="{{ route('articles.edit', ['article' => $article->id]) }}">EDIT</a>
    @endif
@endsection
