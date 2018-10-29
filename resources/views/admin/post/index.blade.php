@extends('layouts.admin')

@section('content')


    @foreach($posts as $post)

    <div class="">
        <div class="col-lg-4 col-xlg-3">
            <div class="card">
                <img class="card-img-top img-responsive" src="{{$post->photo_id ? 'http://localhost/payebahs5.5/storage/posts/thumbnail/'.$post->photo->path : 'http://via.placeholder.com/500x500'}}" alt="Card image cap">
                <div class="card-body">
                    <h3 class="font-normal">{{$post->user->name}}</h3>
                    <p class="m-b-0 m-t-20">{{$post->body}}</p>
                    <div class="d-flex m-t-20">

                        <div class="ml-auto align-self-center">
                            <a href="" class="link m-r-10"><i class="fa fa-heart-o"></i>{{$post->likes->count()}}</a>
                            <a href="" class="link m-r-10"><i class="fa fa-comment-o"></i>{{$post->comments->count()}}</a>
                            @if($post->event)
                            <a href="event/{{$post->event->id}}" class="link m-r-10"><i class="fa fa-share"></i>{{$post->event->name}}</a>
                            @endif


                                {!! Form::open( ['method'=>'DELETE' ,'action' => ['AdminPostController@destroy',$post->id]  , 'class'=>'form-horizontal form-material']) !!}
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

