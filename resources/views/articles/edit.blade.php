@extends('layout')

@section('content')

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('articles.index') }}">Back</a>

    <form action="{{ route('articles.update', ['article' => $article->id]) }}" method="post">

        @csrf
        <input type="hidden" name="_method" value="PUT">
        <label for="idName">Name</label>
        <input id="idName" type="text" name="name" value="{{ old('name', $article->name) }}">

        <label for="idContent">Content</label>
        <input id="idContent" type="text" name="content" value="{{ old('content', $article->content) }}">

        <button type="submit">Save</button>
    </form>

@endsection
