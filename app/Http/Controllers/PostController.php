<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Post;

use App\Tag;

use Auth;

use Redirect;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:100',
            'content' => 'required|max:300',
            'day' => 'required',
            'time' => 'required',
            'privacy' => 'required',
            'image' => 'image|mimes:jpeg,jpg,png,gif'
        ]);

        $words = explode(" ", strip_tags($request->content));
        $tags = array();
        $tagIDs = array();
        foreach ($words as $w) {
          if(strcmp('#', $w[0]) == 0) {
            array_push($tags, $w);
          }
        }

        foreach ($tags as $tag) {
            if (Tag::where('name','=',$tag)->exists()) {
                array_push($tagIDs, Tag::where('name','=',$tag)->first()->id);
            } else {
                $newTag = new Tag(['name' => $tag]);
                $newTag->save();
                array_push($tagIDs, $newTag->id);
            }
        }
        
        $post = new Post();

        if (strcmp($request->image, "") != "") {
            $extension = $request->file('image')->getClientOriginalExtension(); // getting image extension
            $fileName = rand(0,99999).'_'.rand(0,99999); //renaming image
            $fileNameExtension = $fileName.".".$extension;
            $post->image = $fileNameExtension;
            $request->file('image')->move('uploads', $fileNameExtension);   
        }

        $date = date('Y-m-d').' '.$request->time;
        $post->title = strip_tags($request->title);
        $post->content = strip_tags($request->content);
        $post->privacy = $request->privacy;
        $post->date = $date;
        $post->user_id = Auth::id();
        $post->save();
        $post->tags()->sync($tagIDs);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if (strcmp($post->privacy,'public') == 0 || $post->user_id == Auth::id()) {
            $top = Tag::top();
            $data = array(
                'post' => $post,
                'tops' => $top
            );
            return view('profile.post', $data);
        } else {
            return back();            
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if ($post->user_id == Auth::id()) {
            return view('profile.edit', compact('post', $post));
        } else {
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $words = explode(" ", strip_tags($request->content));
        $tags = array();
        $tagIDs = array();
        foreach ($words as $w) {
          if(strcmp('#', $w[0]) == 0) {
            array_push($tags, $w);
          }
        }
        foreach ($tags as $tag) {
            if (Tag::where('name','=',$tag)->exists()) {
                array_push($tagIDs, Tag::where('name','=',$tag)->first()->id);
            } else {
                $newTag = new Tag(['name' => $tag]);
                $newTag->save();
                array_push($tagIDs, $newTag->id);
            }
        }
        if ($post->user_id == Auth::id()) {
            $post->update(['content' => strip_tags($request->content)]);
            $post->tags()->sync($tagIDs);
        }
        return Redirect::to('/post/'.$post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if ($post->user_id == Auth::id()) {
            $post->delete();
        }
        return Redirect::to('/profile/'.Auth::id());
    }

    public function privacy(Post $post)
    {
        if ($post->user_id == Auth::id()) {
            if (strcmp($post->privacy, 'public') == 0) {
                $post->update(['privacy' => 'private']);
            } else {
                $post->update(['privacy' => 'public']);
            }
            if (strcmp($post->privacy, 'public') == 0) {
                return '<span class="label label-success">'.$post->privacy.'</span>';
            } else {
                return '<span class="label label-default">'.$post->privacy.'</span>';
            }
        }
    }
}
