
@extends('layouts.admin')

@section('content')



    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center class="m-t-30">
                        <img src="{{$type->cover_id ? 'http://localhost/payebahs5.5/public/type/cover/thumbnail/'.$type->cover->path : 'http://via.placeholder.com/300x100'}}" class="rounded" width="300" height="100" />
                        <img style="margin-top: -20px; margin-right: 150px" src="{{$type->avatar_id ? 'http://localhost/payebahs5.5/public/type/avatar/'.$type->avatar->path : 'http://via.placeholder.com/50x50'}}" class="img-circle" width="80" />
                        <div class="row text-center justify-content-md-center" style="margin-top: 50px">
                            <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-people"></i> <font class="font-medium">254</font></a></div>
                            <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-picture"></i> <font class="font-medium">54</font></a></div>
                        </div>
                    </center>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <div class="card-body">
                    {!! Form::model($type , ['method'=>'PUT' ,'action' => ['AdminTypeController@update',$type->id] ,'files' => true , 'class'=>'form-horizontal form-material']) !!}
                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::label('name', 'FullName') !!}
                            {!! Form::text('name' ,$type->name,[ 'class'=>'form-control form-control-line' ,  'placeholder'=>'yourname']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="example-email" class="col-md-12">Description</label>
                        <div class="col-md-12">
                            {!! Form::text('description',$type->description,[ 'class'=>'form-control' ,  'placeholder'=>'yourname@example.com']) !!}

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
                            {!! Form::close() !!}
                            {!! Form::open( ['method'=>'DELETE' ,'action' => ['AdminTypeController@destroy' , $type->id] ,'files' => true, 'class'=>'form-horizontal form-material ']) !!}
                            {!! Form::token() !!}
                            {!! Form::submit('delete',[ 'class'=>'btn btn-danger' ] ) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>

@stop

@section('footer')
@stop

