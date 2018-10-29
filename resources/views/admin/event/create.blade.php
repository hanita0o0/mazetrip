
{{--/**--}}
 {{--* Created by PhpStorm.--}}
 {{--* User: hossein--}}
 {{--* Date: 10/16/2017--}}
 {{--* Time: 8:48--}}
 {{--*/--}}


@extends('layouts.admin')

@section('content')



    <div class="row">

        <!-- Column -->
        {{--<div class="col-lg-8 col-xlg-9 col-md-7">--}}
            {{--<div class="card">--}}
                <div class="card-body">
                    {!! Form::open( ['method'=>'POST' ,'action' => ['AdminEventController@store'] ,'files' => true , 'class'=>'']) !!}
                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::label('name', 'Type Name') !!}
                            {!! Form::text('name' ,null,[ 'class'=>'form-control form-control-line']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::label('header', 'Header') !!}
                            {!! Form::text('header' ,null,[ 'class'=>'form-control form-control-line']) !!}
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-12">
                        {!! Form::label('is_active', 'Is active') !!}
                        {!! Form::select('is_active',[1 => 'is_active' , 0=>'not_active'] , null, [ 'class'=>'form-control', ])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                        {!! Form::label('type', 'type') !!}
                        {!! Form::select('type',$type , '', [ 'class'=>'form-control', ])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::label('avatar', 'avatar') !!}
                            {!! Form::file('avatar' ,[ 'class'=>'form-control' ] ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::label('state', 'state') !!}
                            {!! Form::select('state',$state, '', [ 'class'=>'form-control', ])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::label('city', 'city') !!}
                            {!! Form::text('city' ,null,[ 'class'=>'form-control form-control-line']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::label('about_team', 'About Team') !!}
                            {!! Form::textarea('about_team' ,null,[ 'class'=>'form-control form-control-line']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::label('about', 'About') !!}
                            {!! Form::textarea('about' ,null,[ 'class'=>'form-control form-control-line']) !!}
                        </div>
                    </div>


                    <div class="form-group">
                        {!! Form::token() !!}
                        {!! Form::submit('submit',[ 'class'=>'btn btn-primary' ] ) !!}
                    </div>

                    {!! Form::close() !!}

                </div>
            {{--</div>--}}
        {{--</div>--}}
    </div>

@stop

@section('footer')
@stop

