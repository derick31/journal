<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Post;
use App\Tag;
use App\PostTag;
use DB;
use Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::where('privacy','public')->orderBy('date', 'desc')->get();
        $top = Tag::top();
        $data = array(
            'posts' => $posts,
            'tops' => $top,
        );
        return view('home', $data);
    }

    public function tag(Tag $tag)
    {
        $top = Tag::top();
        $posts = $tag->posts()->where('privacy','public')->orderBy('date', 'desc')->get();
        $data = array(
            'posts' => $posts,
            'tag' => $tag,
            'tops' => $top,
        );
        return view('tag', $data);
    }
}
