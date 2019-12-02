@extends('layout')

@section('content')

    Nazov: {{ $article->name }}<br/>
    Obsah: {{ $article->content }}<br/>

    <a href="{{ route('articles.edit', ['article' => $article->id]) }}">EDIT</a>

@endsection
