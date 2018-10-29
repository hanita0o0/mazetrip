
@extends('layouts.admin')

@section('content')

    <h1>Create user</h1>

    {!! Form::open(['method'=>'POST' ,'action' => ['AdminUserController@store'] ,'files' => true]) !!}


    <div class="form-group">
        {!! Form::label('name', 'FullName') !!}
        {!! Form::text('name' ,'',[ 'class'=>'form-control' ,  'placeholder'=>'yourname']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('email', 'email') !!}
        {!! Form::email('email','',[ 'class'=>'form-control' ,  'placeholder'=>'yourname@example.com']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('password', 'password') !!}
        {!! Form::text('password',null ,[ 'class'=>'form-control']) !!}
    </div>


    <div class="form-group">
        {!! Form::label('role', 'Role') !!}
        {!! Form::select('role_id', $role , null, [ 'class'=>'form-control', 'placeholder' => 'Select a Role...'])!!}
    </div>


    <div class="form-group">
        {!! Form::label('state', 'State') !!}
        {!! Form::select('state_id', $state ,'', [ 'class'=>'form-control', 'placeholder' => 'Select a State...'])!!}
    </div>

    <div class="form-group">
        {!! Form::label('city', 'City') !!}
        {!! Form::text('city',null ,[ 'class'=>'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('address', 'Address') !!}
        {!! Form::text('address',null  ,[ 'class'=>'form-control']) !!}
    </div>


    <div class="form-group">
        {!! Form::label('phone', 'Phone') !!}
        {!! Form::text('phone',null  ,[ 'class'=>'form-control'] ) !!}
    </div>


    <div class="form-group">
        {!! Form::label('gender', 'Gender') !!}
        {!! Form::select('gender', [0 => 'female', 1 => 'male'], [ 'class'=>'form-control', 'placeholder' => 'select a gender'])!!}
    </div>


    <div class="form-group">
        {!! Form::label('bio', 'bio') !!}
        {!! Form::textarea('bio',null  ,[ 'class'=>'form-control' ] ) !!}
    </div>

    <div class="form-group">
        {!! Form::label('avatar', 'avatar') !!}
        {!! Form::file('avatar' ,[ 'class'=>'form-control' ] ) !!}
        {!! Form::label('cover', 'cover') !!}
        {!! Form::file('cover' ,[ 'class'=>'form-control' ] ) !!}

    </div>

    <div class="form-group">
        {!! Form::token() !!}
        {!! Form::submit('submit',[ 'class'=>'btn btn-primary' ] ) !!}
    </div>


    {!! Form::close() !!}



@stop

@section('footer')
@stop

