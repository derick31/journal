@extends ('layouts.app')

@section('content')


<div class="container">
    <div class="sidebar col-md-3">
        <div>
            <h2>User name</h2>
        </div>   
    </div>

    <div class="leftbar col-md-2 pull-right">
        <div>
            <h2>Trending</h2>
        </div>
    </div>

    <div class="posts col-md-7">
        <div class="row">
        @foreach ($posts as $post)
            <div class="post">
                <h4>{!! $post->date !!}</h4>
                <h3><a href='post/{!! $post->id !!}'>{!! $post->title !!}</a></h3>
                @if(strcmp($post->image,'') != 0)
                    <img class="img-responsive" src="{{ url('/uploads/'.$post->image) }}">
                @endif
                <p>{!! $post->content !!}</p>
                <button class="comments-button" value="{!! $post->id !!}">show comments</button>
                {!! Form::open(array('route' => array('post.like.store', $post->id ))) !!}
                @if($post->userLikes(Auth::user()))
                    {!! Form::submit('unlike') !!}
                @else
                    {!! Form::submit('like') !!}
                @endif
                {!! Form::close() !!}
                <div class="comment-form">
                {!! Form::open(array('route' => array('post.comment.store', $post->id ))) !!}
                    {!! Form::text('content', '', array('class' => 'form-control', 'placeholder' => 'comment here')) !!}
                {!! Form::close() !!}  
                </div>
                <div class="col-md-11 col-md-offset-1">
                    <div class="comments" id="comments{!! $post->id !!}">
                    @foreach ($post->comments as $comment)
                        <div>
                            <h4>{!! $comment->user->first_name !!}</h4>
                            <p>{!! $comment->content !!}</p>
                        </div>
                        <br>
                    @endforeach
                    </div>
                </div>
            </div>
            <hr>
        @endforeach
        </div>       
    </div>
</div>

@endsection