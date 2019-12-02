@extends('layout')

@section('content')

    <a href="{{ route('articles.create') }}">Add new</a><br><br>

    @foreach($articles as $article)

        Nazov: <a href="{{ route('articles.show', ['article' => $article->id]) }}">{{ $article->name }}</a><br/>
        Obsah: {{ $article->content }}<br/>
        @if($article->user)
            Vytvoril: {{ $article->user->name }}<br/>
        @else
            Vytvoril: anonym<br/>
        @endif

        @if(Auth::user() && Auth::user() == $article->user)
            <form action="{{ route('articles.destroy', ['article' => $article->id]) }}" method="post">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit">Delete</button>
            </form>
        @endif

        <br/>
    @endforeach
@endsection
