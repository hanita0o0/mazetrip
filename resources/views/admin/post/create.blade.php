@extends('layouts.admin')

@section('content')


    {!! Form::open(['method'=>'POST' ,'action' => ['AdminPostController@store'],'files'=>true  , 'class'=>'form-horizontal form-material']) !!}
    {!! Form::text('body' ,'',[ 'class'=>'form-control form-control-line' ,  'placeholder'=>'body']) !!}
    {!! Form::number('event_id' ,'',[ 'class'=>'form-control form-control-line' ,  'placeholder'=>'event_id']) !!}
    {!! Form::number('user_id' ,'',[ 'class'=>'form-control form-control-line' ,  'placeholder'=>'user_id']) !!}
    {!! Form::file('photo' ,[ 'class'=>'form-control' ] ) !!}


    {!! Form::token() !!}
    {!! Form::submit('create post',[ 'class'=>'btn btn-success' ] ) !!}
    {!! Form::close() !!}


@stop

@section('footer')
@stop

