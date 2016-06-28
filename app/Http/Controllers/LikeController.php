<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Post;
use App\Like;

use Auth;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post)
    {

        if ($post->userLikes(Auth::user())) {
            $post->likes()->where('user_id', Auth::id())->delete();
            return "<span class='glyphicon glyphicon-star-empty text-danger' aria-hidden='true'></span>".count($post->likes);
        } else {
            $like = new Like();
            $like->user_id = Auth::id();
            $post->likes()->save($like);
            return "<span class='glyphicon glyphicon-star text-success' aria-hidden='true'></span>".count($post->likes);
        }
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        if ($post->userLikes(Auth::user())) {
            $post->likes()->where('user_id', Auth::id())->delete();
        } else {
            $like = new Like();
            $like->user_id = Auth::id();
            $post->likes()->save($like);
            return back();
        }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
