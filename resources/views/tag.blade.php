@extends ('layouts.app')

@section('content')

<div class="container">
    
    <div class="sidebar col-md-3">
        <div class="username">
            <h2>{!! $tag->name !!}</h2>
            <h3>Trending</h3>
            @foreach($tops as $top)
                <span><a href="{{ url('/tag/'.$top->id)}}">{{ $top->name }}</a></span>
            @endforeach
        </div>   
    </div>

    <div class="posts col-md-9" >
        <div class="row" style="border-radius:10px">
        @foreach ($posts as $post)
            <div id="post{{ $post->id }}">
                <div class="post-preview" style="background: rgba(255, 255, 255, 0.8); padding:15px;border-radius:10px;margin-bottom:15px">
                    <a href="{{ url('post/'.$post->id) }}">
                        <h2 class="post-title" style=" margin-top:10px">{!! $post->title !!}</h2>
                    </a>
                    <p class="post-meta">Posted by <a href="{{ url('/profile/'.$post->user_id) }}">{!! $post->user->first_name !!}</a> on {!! date('M d, Y', strtotime($post->date)) !!}</p>
                    @if(strcmp($post->image,'') != 0)
                        <img class="img-responsive" src="{{ url('/uploads/'.$post->image) }}">
                    @endif
                    <p class="post-subtitle">
                    " 
                    @foreach(explode(" ", $post->content) as $word)
                        @if(strcmp('#', $word[0]) == 0)
                            {!! '<a href="'.url('/tag/'.$post->tags()->where('name','=',$word)->first()->id).'" class="tag">'.$word.'</a>' !!} 
                        @else
                            {!! $word !!} 
                        @endif
                    @endforeach
                    "
                    </p>
                    <div class="comment-form">
                        <div class="form-group">
                        <form action="#" class="comment-form" id="{{ $post->id }}">
                            <input type="text" class="form-control" id="comment{{ $post->id }}" placeholder="comment here">
                        </form>
                        </div> 
                    </div>
                    <div class="btn-group btn-group-justified" role="group" aria-label="...">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-default like-button" id="like{!! $post->id !!}" value="{!! $post->id !!}">
                            @if($post->userLikes(Auth::user()))
                                <span class="glyphicon glyphicon-star text-success" aria-hidden="true"></span>
                            @else
                                <span class="glyphicon glyphicon-star-empty text-danger" aria-hidden="true"></span>
                            @endif
                            {{ count($post->likes) }}
                            </button>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-default">Middle</button>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="comments-button btn btn-default" value="{!! $post->id !!}"">
                                <span id="count{!! $post->id !!}">{!! count($post->comments) !!}</span> comments 
                                <span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                    <div class="comments" id="comments{!! $post->id !!}" style="margin-left:20px">
                    @foreach ($post->comments as $comment)
                        <div>
                            <h3>{!! $comment->user->first_name !!}  
                            <small>said " {!! $comment->content !!} "</small>
                            </h3>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        @endforeach
        </div>       
    </div>
</div>

@endsection