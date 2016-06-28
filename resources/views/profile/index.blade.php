@extends ('layouts.app')

@section('content')

<div class="container">
    <div class="sidebar col-md-3">
        <div class="username">
            <h2>{!! $user->first_name !!}</h2>
            <h3>Trending</h3>
            @foreach($tops as $top)
                <span><a href="{{ url('/tag/'.$top->id)}}">{{ $top->name }}</a></span>
            @endforeach
        </div>   
    </div>
    <div class="posts col-md-9" >
        @if($user->id == Auth::id())
        <div class="row custom-panel">
            <button type="button" class="btn btn-primary btn-block" id="write-button">Write</button>
            <div id="write">
                <input type="hidden" name="errors" id="error-count" value="{{ count($errors) }}">
                {!! Form::open(array('route' => 'post.store', 'files' => 'true')) !!}
                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                    {!! Form::label('title','Title') !!}
                    {!! Form::text('title', '', array('class' => 'form-control')) !!}
                    @if ($errors->has('title'))
                        <span class="help-block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                    {!! Form::label('image','Upload') !!}
                    {!! Form::file('image', '',array('class' => 'form-control', 'accept' => 'image/*')) !!}
                    @if ($errors->has('image'))
                        <span class="help-block">
                            <strong>{{ $errors->first('image') }}</strong>
                        </span>
                    @endif
                </div>
                <div id="image-container">
                    <span class="glyphicon glyphicon-remove pull-right text-danger" aria-hidden="true" id="cancel-image"></span>
                    <img class="img-responsive" src="" id="image-upload"/>
                </div>
                <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                    {!! Form::label('content','Content') !!}
                    {!! Form::textarea('content', '', array('class' => 'form-control', 'size' => '30x4')) !!}
                    @if ($errors->has('content'))
                        <span class="help-block">
                            <strong>{{ $errors->first('content') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Form::hidden('day', date('Y-m-d'), array('class' => 'form-control', 'id' => 'input-day')) !!}
                </div>
                <div class="form-group{{ $errors->has('time') ? ' has-error' : '' }}">
                    {!! Form::label('time','Time', array('class' => 'control-label')) !!}
                    {!! Form::time('time', date('H:m'), array('class' => 'form-control')) !!}
                    @if ($errors->has('time'))
                        <span class="help-block">
                            <strong>{{ $errors->first('time') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Form::label('private') !!}
                    {!! Form::radio('privacy', 'private', array('class' => 'form-control')) !!}
                    {!! Form::label('public') !!}
                    {!! Form::radio('privacy', 'public', array('class' => 'form-control')) !!}
                </div> 
                {!! Form::submit('Add', array('class' => 'btn btn-primary')) !!}
                {!! Form::reset('Cancel', array('class' => 'btn btn-primary', 'id' => 'write-cancel')) !!}
                {!! Form::close() !!}
            </div>
        </div>
        @endif
        <div class="row" style="border-radius:10px">
        @foreach ($posts as $post)
        <div id="post{{ $post->id }}">
            <div class="post-preview custom-panel">
                @if($post->user_id == Auth::id())
                <div class="dropdown pull-right">
                    <a style="cursor:pointer" class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="glyphicon glyphicon-cog text-default"></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href={{ url('post/'.$post->id.'/edit') }}><span class="glyphicon glyphicon-pencil text-success" aria-hidden="true"></span> Edit</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="" data-toggle="modal" data-target="#modal" id="{!! $post->id !!}">
                            <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true" title="remove"></span> Remove
                        </a></li>
                    </ul>
                </div>
                @endif
                <a href={{ url('post/'.$post->id) }}>
                    <h2 class="post-title" style=" margin-top:10px">{!! strip_tags($post->title) !!}</h2>
                </a>
                <p class="post-meta">
                    Written by <a href="{{ url('/profile/'.$post->user_id) }}">{!! $post->user->first_name !!}</a> on {!! date('M d, Y  h:m a', strtotime($post->date)) !!}
                    @if($post->user_id == Auth::id()) 
                    as 
                    <a style="cursor:pointer" class="privacy-toggle" id="privacy-{!! $post->id !!}">
                        <span class="label label-{{ strcmp($post->privacy,'public')==0 ? 'success' : 'default' }}">{!! $post->privacy !!}</span>
                    </a>
                    @endif
                </p>
                @if(strcmp($post->image,'') != 0)
                    <img class="img-responsive" src="{{ url('/uploads/'.$post->image) }}">
                    <div class="dropdown pull-right">
                        <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <span class="glyphicon glyphicon-th-large pull-right text-default" aria-hidden="true" id="cancel-image"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a style="cursor:pointer" class="change-cover" id="{!! $post->image !!}">Make this your background</a></li>
                        </ul>
                    </div>
                @endif
                <p class="post-subtitle">
                " 
                @foreach(explode(" ", strip_tags($post->content)) as $word)
                    @if(strcmp('#', $word[0]) == 0)
                        {!! '<a href="'.url('/tag/'.$post->tags()->where('name','=',$word)->first()->id).'" class="tag">'.$word.'</a>' !!} 
                    @else
                        {!! $word !!} 
                    @endif
                @endforeach
                "
                </p>
                <div class="form-group">
                    <form action="#" class="comment-form" id="{{ $post->id }}">
                        <input type="text" class="form-control" id="comment{{ $post->id }}" placeholder="comment here">
                    </form>
                </div>
                <div class="btn-group btn-group-justified" role="group" aria-label="...">
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-default like-button" id="like{!! $post->id !!}" value="{!! $post->id !!}">
                        @if($post->userLikes(Auth::user()))
                            <span class="glyphicon glyphicon-star text-success" aria-hidden="true"></span>
                        @else
                            <span class="glyphicon glyphicon-star-empty text-danger" aria-hidden="true"></span>
                        @endif
                        {!! count($post->likes) !!}
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
                        <small>said " {!! strip_tags($comment->content) !!} "</small>
                        </h3>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
        @endforeach
        </div> 
        <div 
             class="modal fade" 
             id="modal"
             role="dialog"
             aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalLabel">sample</h4>
                    </div>
                    <div class="modal-body">
                        <span>Are you sure you want to remove this?.</span>
                    </div>
                    <div class="modal-footer">
                        <button 
                            type="button" 
                            class="btn btn-sm btn-danger remove-button" 
                            data-dismiss="modal"
                            >
                            Yes
                        </button>
                        <button 
                            type="button" 
                            class="btn btn-sm btn-default" 
                            data-dismiss="modal">
                            No
                        </button>
                    </div>
                </div>
            </div>
        </div>      
    </div>
</div>

@endsection