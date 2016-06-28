@extends ('layouts.app')

@section ('content')
 
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="username">
                    <h1>{!! Auth::user()->first_name." ".Auth::user()->last_name !!}</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7" >
                <div id="write" style="background: rgba(255, 255, 255, 0.8); padding:15px;border-radius:10px">
                    <input type="hidden" name="errors" id="error-count" value="{{ count($errors) }}">
                    {!! Form::open(array('route' => 'post.store', 'files' => 'true')) !!}
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        {!! Form::label('title','Title', array('class' => 'control-label')) !!}
                        {!! Form::text('title', '', array('class' => 'form-control')) !!}
                        @if ($errors->has('title'))
                            <span class="help-block">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        {!! Form::label('image','Upload') !!}
                        {!! Form::file('image', '',array('class' => 'form-control', 'accept' => 'image/*')) !!}
                    </div>
                    <div id="image-container">
                        <span class="glyphicon glyphicon-remove pull-right text-danger" aria-hidden="true" id="cancel-image"></span>
                        <img class="img-responsive" src="" id="image-upload"/>
                    </div>
                    <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                        {!! Form::label('content','Content', array('class' => 'control-label')) !!}
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
                    <!-- <button type="button" class="btn btn-primary" id="write-cancel">Cancel</button>   -->
                {!! Form::close() !!}
                </div>
                <div id="calendar" style="background: rgba(255, 255, 255, 0.8); padding:15px;border-radius:10px;">
                    {!! $calendar !!}
                    <div class="btn-group btn-group-justified">
                        <a class="btn btn-primary" href="calendar?month={{ date('m',$date)-1 }}&year={{ date('Y', $date) }}"><span class="glyphicon glyphicon-chevron-left"></span>Prev</a>
                        <a class="btn btn-primary" href="calendar?month={{ date('m',$date)+1 }}&year={{ date('Y', $date) }}">Next<span class="glyphicon glyphicon-chevron-right"></span></a>
                        <a class="btn btn-info" href="calendar">Today</a>
                        <div class="btn-group">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-calendar" style="height:100%">
                            Go to <span class="glyphicon glyphicon-th-large"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div style="background: rgba(255, 255, 255, 0.8); padding:15px;border-radius:10px;">
                <div>
                    <h1 ><strong><span id="selected-date">{!! date('M d, Y', $date) !!}</span></strong></h1>
                </div>
                <div class="table-responsive" style="overflow:visible">
                    <table class="table table-hover" id="posts-table">
                        <caption>Posts</caption>
                        <thead>
                            <tr>
                                <th rowspan="1">Time</th>
                                <th rowspan="4">Title</th>
                            </tr>
                        </thead>
                        <tbody id="posts-body">
                            @if(count($posts) == 0)
                                <tr><td><h3>no posts found</h3></td></tr>
                            @else 
                            @foreach($posts as $post) 
                                <tr id="post{{ $post->id }}">
                                    <td>{!! date('H:m', strtotime($post->date)) !!}</td>
                                    <td><a href='post/{!! $post->id !!}'>{!! $post->title !!}</a></td>
                                    <td>
                                        <a style="cursor:pointer" class="privacy-toggle" id="privacy-{!! $post->id !!}">
                                            <span class="label label-{{ strcmp($post->privacy,'public')==0 ? 'success' : 'default' }}">{!! $post->privacy !!}</span>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                <li><a href="post/{{ $post->id}}/edit"><span class="glyphicon glyphicon-pencil text-success" aria-hidden="true"></span> Edit</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="" data-toggle="modal" data-target="#modal" id="{!! $post->id !!}">
                                                    <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true" title="remove"></span> Remove
                                                </a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif    
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary btn-block" id="write-button">Write</button>
                </div>
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
                <div 
                     class="modal fade" 
                     id="modal-calendar"
                     role="dialog"
                     aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modalLabel">Go to</h4>
                            </div>
                                <form class="form" method="get" action="calendar">
                            <div class="modal-body">
                                    <div id="input-month" class="form-group">
                                        <select class="form-control" name="month" id="sel1">
                                            @for($i=1; $i<=12; $i++)
                                                <option name="month" {!! date('n', $date) == $i?'selected':'' !!} value="{{ $i }}">{!! date('F', mktime(null, null, null, $i)) !!}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input id="input-year" type="number" class="form-control" name="year" value="{{ date('Y', $date) }}" placeholder="xxxx" maxlength="4" max="9999" min="1">
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" type="submit">Go</button>
                                <button class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
                            </div>
                                </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection