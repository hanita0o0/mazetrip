@extends('layouts.admin')

@section('content')


    @foreach($events as $event)

        <div class="">
            <div class="col-lg-4 col-xlg-3">
                <div class="card">
                    <img class="card-img-top img-responsive" src="{{$event->avatar ? 'http://localhost/payebahs5.5/storage/events/event_profile/thumbnail/'.$post->avatarImage->path : 'http://via.placeholder.com/500x500'}}" alt="Card image cap">
                    <div class="card-body">
                        <h3 class="font-normal">{{$event->name}}</h3>
                        <p class="m-b-0 m-t-20">{{$event->about}}</p>
                        <div class="d-flex m-t-20">

                            <div class="ml-auto align-self-center">
                                <a href="" class="link m-r-10"><i class="fa fa-heart-o"></i>{{$event->subscribers->count()}}</a>
                                <a href="" class="link m-r-10"><i class="fa fa-comment-o"></i>{{$event->tickets->count()}}</a>

                                {!! Form::open( ['method'=>'DELETE' ,'action' => ['AdminEventController@destroy',$event->id]  , 'class'=>'form-horizontal form-material']) !!}
                                {!! Form::token()!!}
                                {!! Form::submit('delete',[ 'class'=>'btn btn-danger' ] )!!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endforeach



@stop

@section('footer')
@stop

