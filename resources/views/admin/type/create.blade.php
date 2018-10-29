
@extends('layouts.admin')

@section('content')



    <div class="row">

        <!-- Column -->
        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <div class="card-body">
                    {!! Form::open( ['method'=>'POST' ,'action' => ['AdminTypeController@store'] ,'files' => true , 'class'=>'form-horizontal form-material']) !!}
                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::label('name', 'Type Name') !!}
                            {!! Form::text('name' ,null,[ 'class'=>'form-control form-control-line']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12">Description</label>
                        <div class="col-md-12">
                            {!! Form::text('description',null ,[ 'class'=>'form-control']) !!}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-12"></label>
                        <div class="col-md-12">
                            {!! Form::label('avatar', 'avatar') !!}
                            {!! Form::file('avatar' ,[ 'class'=>'form-control' ] ) !!}
                            {!! Form::label('cover', 'cover') !!}
                            {!! Form::file('cover' ,[ 'class'=>'form-control' ] ) !!}
                        </div>
                    </div>




                    <div class="form-group">
                        <div class="col-sm-12">
                            {!! Form::token() !!}
                            {!! Form::submit('update',[ 'class'=>'btn btn-success' ] ) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

@stop

@section('footer')
@stop

