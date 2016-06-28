@extends ('layouts.app')

@section('content')

<div class="container">
    <div class="sidebar col-md-3">
        <div class="username">
            <h2>{!! Auth::user()->first_name !!}</h2>
        </div>   
    </div>

    <div class="posts col-md-9">
        <div class="row">
            <div class="post-preview" style="background: rgba(255, 255, 255, 0.8);padding:15px;border-radius:10px">
                <h2 class="post-title"><a href='post/{!! $post->id !!}'>{!! $post->title !!}</a></h2>
                <p class="post-meta">
                    Posted by <a href="{{ url('/profile/'.$post->user_id) }}">{!! $post->user->first_name !!}</a> on {!! date('M d, Y', strtotime($post->date)) !!}
                    @if($post->user_id == Auth::id()) as <a style="cursor:pointer" class="privacy-toggle" id="privacy-{!! $post->id !!}">{!! $post->privacy !!}</a>@endif
                </p>
                @if(strcmp($post->image,'') != 0)
                    <img class="img-responsive" src="{{ url('/uploads/'.$post->image) }}">
                @endif
                <br>
                {!! Form::open(array('route' => array('post.update', $post->id), 'method' => 'patch')) !!}
                    <div class="form-group"> 
                    {!! Form::textarea('content', $post->content, array('class' => 'form-control', 'rows' => '10')) !!}
                    </div>
                    <div class="form-group">
                    {!! Form::submit('Submit', array('class' => 'btn btn-primary')) !!}
                    <a href="{{ URL::previous() }}" type="button" class="btn btn-primary" >Cancel</a>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>      
    </div>
</div>

@endsection