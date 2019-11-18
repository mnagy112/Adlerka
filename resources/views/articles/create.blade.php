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

    <form action="{{ route('articles.store') }}" method="post">

        @csrf
        <label for="idName">Name</label>
        <input id="idName" type="text" name="name">

        <label for="idContent">Content</label>
        <input id="idContent" type="text" name="content">

        <button type="submit">Save</button>
    </form>

@endsection
