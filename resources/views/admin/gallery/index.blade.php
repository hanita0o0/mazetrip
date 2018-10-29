@extends('layouts.admin')

@section('content')
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                @foreach($users as $user)

                <div class="card-body">
                    <center class="m-t-30" >
                        <img src="{{$user->cover_id ? 'http://localhost/payebahs5.5/public/user/cover/thumbnail/'.$user->cover->path : 'http://via.placeholder.com/300x100'}}" class="rounded" width="300" height="100" />
                        <img style="margin-top: -20px; margin-right: 150px" src="{{$user->avatar_id ? 'http://localhost/payebahs5.5/public/user/avatar/'.$user->avatar->path : 'http://via.placeholder.com/50x50'}}" class="img-circle" width="80" />
                        <div class="row text-center justify-content-md-center" style="margin-top: 50px">
                            <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-people"></i> <font class="font-medium">254</font></a></div>
                            <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-picture"></i> <font class="font-medium">54</font></a></div>

                        </div>
                        @foreach($user->gallery as $photo)
                            <div style=" display: inline-block; ">
                                <a href="{{route('gallery.edit',$photo->id)}}"><img style="margin-top: -20px; margin-right: 150px;" src="{{'http://localhost/payebahs5.5/public/user/avatar/'.$photo->path}}"  width="300" /></a>
                            </div>
                        @endforeach
                    </center>
                </div>
                @endforeach
            </div>
        </div>

@stop



@section('footer')
@stop
