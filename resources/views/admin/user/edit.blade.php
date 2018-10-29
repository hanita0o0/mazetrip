
@extends('layouts.admin')

@section('content')



    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center class="m-t-30">
                        <img src="{{$user->cover_id ? 'http://localhost/payebahs5.5/public/user/cover/thumbnail/'.$user->cover->path : 'http://via.placeholder.com/300x100'}}" class="rounded" width="300" height="100" />
                        <img style="margin-top: -20px; margin-right: 150px" src="{{$user->avatar_id ? 'http://localhost/payebahs5.5/storage/users/avatar/thumbnail/'.$user->avatar->path : 'http://via.placeholder.com/50x50'}}" class="img-circle" width="80" />
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
                        {!! Form::model($user , ['method'=>'PUT' ,'action' => ['AdminUserController@update',$user->id] ,'files' => true , 'class'=>'form-horizontal form-material']) !!}
                        <div class="form-group">
                            <div class="col-md-12">
                                {!! Form::label('name', 'FullName') !!}
                                {!! Form::text('name' ,$user->name,[ 'class'=>'form-control form-control-line' ,  'placeholder'=>'yourname']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="example-email" class="col-md-12">Email</label>
                            <div class="col-md-12">
                                {!! Form::email('email',$user->email,[ 'class'=>'form-control' ,  'placeholder'=>'yourname@example.com']) !!}

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Password</label>
                            <div class="col-md-12">
                                {!! Form::text('password',null ,[ 'class'=>'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">Role</label>
                            <div class="col-md-12">
                                @if(isset($user->role->name))
                                    <h6>the default value was <h6 style="color: #2ca02c">{{$user->role->name}}</h6></h6>
                                @else
                                    <h6>there is no role for this user</h6>
                                @endif
                                    {!! Form::select('role_id',$role,'', [ 'class'=>'form-control' ])!!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">State</label>
                            <div class="col-md-12">
                                {!! Form::select('state_id', $state ,'', [ 'class'=>'form-control'])!!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">City</label>
                            <div class="col-md-12">
                                {!! Form::text('city', null,[ 'class'=>'form-control']) !!}
                            </div>
                        </div>




                        <div class="form-group">
                            <label class="col-md-12">Address</label>
                            <div class="col-md-12">
                                {!! Form::text('address',null  ,[ 'class'=>'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">phone</label>
                            <div class="col-md-12">
                                {!! Form::text('phone',null  ,[ 'class'=>'form-control'] ) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">gender</label>
                            <div class="col-md-12">
                                {!! Form::select('gender', [0 => 'female', 1 => 'male'], null , [ 'class'=>'form-control'])!!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">bio</label>
                            <div class="col-md-12">
                                {!! Form::textarea('bio',null  ,[ 'class'=>'form-control' ] ) !!}
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
                                {!! Form::open( ['method'=>'DELETE' ,'action' => ['AdminUserController@destroy' , $user->id] ,'files' => true, 'class'=>'form-horizontal form-material ']) !!}
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

