<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::all();

        return view('articles.index')->with(['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'content' => 'required',
        ], [
            'name.required' => 'Pole Meno je povinne',
            'content.required' => 'Pole Obsah je povinne',
        ]);

        $article = new Article();
        $article->name = $request['name'];
        $article->content = $request['content'];
        $article->save();

        return redirect()->route('articles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return void
     */
    public function show($id)
    {
        $article = Article::find($id);

        if (!$article) {
            abort(404);
        }

        return view('articles.show')->with(['article' => $article]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::find($id);

        if (!$article) {
            abort(404);
        }

        return view('articles.edit')->with(['article' => $article]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'content' => 'required',
        ], [
            'name.required' => 'Pole Meno je povinne',
            'content.required' => 'Pole Obsah je povinne',
        ]);

        $article = Article::find($id);
        $article->name = $request['name'];
        $article->content = $request['content'];
        $article->save();

        return redirect()->route('articles.show', ['article' => $article->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);

        if ($article) {
            $article->delete();
        }

        return redirect()->route('articles.index');
    }
}
