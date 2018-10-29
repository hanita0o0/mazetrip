
@extends('layouts.admin')

@section('content')



    <img style="float: left" src="{{$event->avatar ? 'http://localhost/payebahs5.5/storage/events/event_profile/medium/'.$event->avatarImage->path : 'http://via.placeholder.com/50x50'}}" class="img-circle" width="80" />
    <h1 style="margin-top: 20px;margin-left: 100px; float: right">{{$event->name}}</h1>

    {!! Form::model($event , ['method'=>'PATCH' ,'action' => ['AdminEventController@addManager',$event->id]  , 'class'=>'form-horizontal form-material']) !!}
    {!! Form::text('name' ,'',[ 'class'=>'form-control form-control-line' ,  'placeholder'=>'yourname']) !!}
    {!! Form::text('role' ,'',[ 'class'=>'form-control form-control-line' ,  'placeholder'=>'role']) !!}
    {!! Form::text('about' ,'',[ 'class'=>'form-control form-control-line' ,  'placeholder'=>'about']) !!}
    {!! Form::token() !!}
    {!! Form::submit('add manager',[ 'class'=>'btn btn-success' ] ) !!}
    {!! Form::close() !!}

    <br>
@foreach($managers as $manager)

        <!-- Column -->
        <div class="col-lg-4 col-xlg-3 col-md-5 row" style="top: 50px;">
            <div class="card">
                <div class="card-body">
                    <center class="m-t-30">
                        <img src="{{$manager->cover_id ? 'http://localhost/payebahs5.5/public/user/cover/thumbnail/'.$manager->cover->path : 'http://via.placeholder.com/300x100'}}" class="rounded" width="300" height="100" />
                        <img style="margin-top: -20px; margin-right: 150px" src="{{$manager->avatar_id ? 'http://localhost/payebahs5.5/public/user/avatar/'.$manager->avatar->path : 'http://via.placeholder.com/50x50'}}" class="img-circle" width="80" />
                        <div class="row text-center justify-content-md-center" >
                            <div><h6 style="float: left">{{$manager->name}}</h6></div>
                        <br>
                            <div><h6 style="float: left">{{$manager->pivot->role}}</h6></div>
                        <br>
                        <div><h6 style="float: left">{{$manager->pivot->about}}</h6></div>
                        </div>
                    </center>
                    <div style="float: right">
                    <a  route="{{url('../admin/user')}}">delete manager</a>
                    {!! Form::open( ['method'=>'DELETE' ,'action' => ['AdminEventController@deleteManager' ,$event->id, $manager->id ] ,'files' => true, 'class'=>'form-horizontal form-material']) !!}
                    {!! Form::token() !!}
                    {!! Form::submit('delete',[ 'class'=>'btn btn-danger' ] ) !!}
                    {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
    @endforeach
    <br>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">subscribers</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>User name</th>
                            <th>delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($subscribers as $subscriber)
                        <tr>
                            <td>{{$subscriber->id}}</td>
                            <td>{{$subscriber->name}}</td>
                            <td>have to add the button</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
{{--this is a between section--}}

    <br>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">chats</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>User name</th>
                            <th>content</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($messages as $message)
                            <tr>
                                <td>{{$message->writer->id}}</td>
                                <td>{{$message->writer->name}}</td>
                                <td>{{$message->content}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{--favorites by section--}}
    <br>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">favorites</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>User name</th>
                            <th>email</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($favorites as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{--adding the favorit section--}}

@if(isset($filter))
    <br>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">requests</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>User name</th>
                            <th>status</th>
                            <th>approve</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($filter as $request)
                            <tr>
                                <td>{{$request->id}}</td>
                                <td>{{$request->user->name}}</td>
                                @if($request->status == 0)
                                <td>need approval</td>
                                @elseif($request->status == 1)
                                <td>rejected</td>
                                @elseif($request->status == 2 )
                                <td>approved</td>
                                @endif
                                <td class="row">
                                    {!! Form::open( ['method'=>'PUT' ,'action' => ['AdminEventController@handleRequest',$request->id]  , 'class'=>'form-horizontal form-material']) !!}
                                    {!! Form::token()!!}
                                    <input name="status" type="hidden" value="{{2}}">
                                    <input name="event_id" type="hidden" value="{{$event->id}}">
                                    {!! Form::submit('approve',[ 'class'=>'btn btn-success' ] )!!}
                                    {!! Form::close() !!}
                                    {!! Form::open( ['method'=>'PUT' ,'action' => ['AdminEventController@handleRequest',$request->id]  , 'class'=>'form-horizontal form-material']) !!}
                                    {!! Form::token()!!}
                                    <input name="status" type="hidden" value="{{1}}">
                                    <input name="event_id" type="hidden" value="{{$event->id}}">
                                    {!! Form::submit('reject',[ 'class'=>'btn btn-danger' ] )!!}
                                    {!! Form::close() !!}


                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
    {{--tickets section --}}
    <br>

    {!! Form::open(['method'=>'PUT' ,'action' => ['AdminEventController@createTicket',$event->id]  , 'class'=>'form-horizontal form-material']) !!}
    {!! Form::text('name' ,'',[ 'class'=>'form-control form-control-line' ,  'placeholder'=>'ticket Name']) !!}
    {!! Form::text('body' ,'',[ 'class'=>'form-control form-control-line' ,  'placeholder'=>'about']) !!}
    {!! Form::text('limit' ,'',[ 'class'=>'form-control form-control-line' ,  'placeholder'=>'limit']) !!}
    {!! Form::text('request' ,'',[ 'class'=>'form-control form-control-line' ,  'placeholder'=>'if require request please type YES']) !!}

    <input name="date" size="50" type="text" value="{{\Carbon\Carbon::now()}}" readonly class="form_datetime form-control form-control-line">

    {!! Form::token() !!}
    {!! Form::submit('add ticket',[ 'class'=>'btn btn-success' ] ) !!}
    {!! Form::close() !!}
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">tickets</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>ticket name</th>
                            <th>users name</th>
                            <th>users count</th>
                            <th>delete</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>{{$ticket->id}}</td>
                                <td>{{$ticket->name}}</td>
                                <td>
                                    @foreach($ticket->user as $user)
                                        {{$user->name}},

                                    @endforeach
                                </td>
                                <td>

                                    {!! Form::open( ['method'=>'DELETE' ,'action' => ['AdminEventController@deleteTicket',$ticket->id,$event->id]  , 'class'=>'form-horizontal form-material']) !!}
                                    {!! Form::token()!!}
                                    {!! Form::submit('delete',[ 'class'=>'btn btn-danger' ] )!!}
                                    {!! Form::close() !!}
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{--handeling ticket requests--}}

    @if(isset($ticketrequests))
        <br>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">requests for tickets</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>User name</th>
                                <th>ticket id</th>
                                <th>ticket name</th>
                                <th>status</th>
                                <th>approve</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ticketrequests as $request)
                                <tr>
                                    <td>{{$request->id}}</td>
                                    <td>{{$request->user->name}}</td>
                                    <td>{{$request->filter->filterable->id}}</td>
                                    <td>{{$request->filter->filterable->name}}</td>
                                    @if($request->status == 0)
                                        <td>need approval</td>
                                    @elseif($request->status == 1)
                                        <td>rejected</td>
                                    @elseif($request->status == 2 )
                                        <td>approved</td>
                                    @endif
                                    <td class="row">
                                        {!! Form::open( ['method'=>'PUT' ,'action' => ['AdminEventController@handleRequest',$request->id]  , 'class'=>'form-horizontal form-material']) !!}
                                        {!! Form::token()!!}
                                        <input name="status" type="hidden" value="{{2}}">
                                        <input name="request_type" type="hidden" value="1">
                                        <input name="event_id" type="hidden" value="{{$event->id}}">
                                        {!! Form::submit('approve',[ 'class'=>'btn btn-success' ] )!!}
                                        {!! Form::close() !!}
                                        {!! Form::open( ['method'=>'PUT' ,'action' => ['AdminEventController@handleRequest',$request->id]  , 'class'=>'form-horizontal form-material']) !!}
                                        {!! Form::token()!!}
                                        <input name="status" type="hidden" value="{{1}}">
                                        <input name="request_type" type="hidden" value="1">
                                        <input name="event_id" type="hidden" value="{{$event->id}}">
                                        {!! Form::submit('reject',[ 'class'=>'btn btn-danger' ] )!!}
                                        {!! Form::close() !!}


                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

{{--part for adding the timeline--}}
    {!! Form::open(['method'=>'POST' ,'action' => ['AdminEventController@createTimeline'],'files'=>true  , 'class'=>'form-horizontal form-material']) !!}
    {!! Form::text('name' ,'',[ 'class'=>'form-control form-control-line' ,  'placeholder'=>'timeline Name']) !!}
    {!! Form::text('text' ,'',[ 'class'=>'form-control form-control-line' ,  'placeholder'=>'about']) !!}
    {!! Form::file('photo' ,[ 'class'=>'form-control' ] ) !!}
    <input name="event_id" type="hidden" value="{{$event->id}}">

    <input name="time" size="50" type="text" value="{{\Carbon\Carbon::now()}}"  class="form_datetime form-control form-control-line">

    {!! Form::token() !!}
    {!! Form::submit('add timeline',[ 'class'=>'btn btn-success' ] ) !!}
    {!! Form::close() !!}





@if(isset($timelines))
    <br>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Time Lines</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>timeline name</th>
                            <th>time</th>
                            <th>photo</th>
                            <th>text</th>
                            <th>edit</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($timelines as $timeline)
                            <tr>
                                <td>{{$timeline->id}}</td>
                                <td>{{$timeline->name}}</td>
                                <td><img src="{{$timeline->photo_id ? 'http://localhost/payebahs5.5/storage/events/event_timeline/thumbnail/'.$timeline->photo->path : 'http://via.placeholder.com/300x100'}}" class="rounded" width="300" height="100" /></td>
                                <td>{{$timeline->text}}</td>
                                <td class="row">
                                    {!! Form::open( ['method'=>'DELETE' ,'action' => ['AdminEventController@deleteTimeline',$event->id,$timeline->id]  , 'class'=>'form-horizontal form-material']) !!}
                                    {!! Form::token()!!}
                                    {!! Form::submit('delete',[ 'class'=>'btn btn-danger' ] )!!}
                                    {!! Form::close() !!}


                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif


    {{--showing posts --}}
@if(isset($posts))

    <br>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">posts</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>timeline name</th>
                            <th>photo</th>
                            <th>body</th>
                            <th>delete</th>


                        </tr>
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>{{$post->id}}</td>
                                <td>{{$post->user->name}}</td>
                                <td><img src="{{$post->photo_id ? 'http://localhost/payebahs5.5/storage/posts/thumbnail/'.$post->photo->path : 'http://via.placeholder.com/300x100'}}" class="rounded" width="300" height="100" /></td>
                                <td>{{$post->body}}</td>
                                <td class="row">
                                    {!! Form::open( ['method'=>'DELETE' ,'action' => ['AdminPostController@destroy',$event->id]  , 'class'=>'form-horizontal form-material']) !!}
                                    {!! Form::token()!!}
                                    {!! Form::submit('delete',[ 'class'=>'btn btn-danger' ] )!!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif














    {{--this part is end of contain part--}}

@stop

@section('footer')
@stop

