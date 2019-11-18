@extends('layout')

@section('content')

    <a href="{{ route('articles.create') }}">Add new</a><br><br>

    @foreach($articles as $article)

        Nazov: {{ $article->name }}<br/>
        Obsah: {{ $article->content }}<br/>
        <form action="{{ route('articles.destroy', ['article' => $article->id]) }}" method="post">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit">Delete</button>
        </form>

    @endforeach
@endsection
