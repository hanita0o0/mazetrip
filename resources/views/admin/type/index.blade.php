@extends('layouts.admin')

@section('content')

    <div class="row">
        <!-- column -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Photo</th>
                                <th>id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>edit</th>
                            </tr>
                            </thead>
                            <tbody>



                            @if($types)
                                @foreach($types as $type)
                                    <tr>
                                        <td> <img src="{{$type->avatar_id ? 'http://localhost/payebahs5.5/public/type/avatar/'.$type->avatar->path : 'http://via.placeholder.com/50x50'}}" alt="user" width="50" class="round"></td>
                                        <td>{{$type->id}}</td>
                                        <td>{{$type->name ? $type->name : null}}</td>
                                        <td>{{$type->description ? $type->description : null}}</td>
                                        <td><a href="{{route('type.edit',$type->id)}}" class="btn waves-effect waves-light btn-info hidden-md-down">update</a></td>

                                    </tr>
                                @endforeach
                            @endif


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



@stop

@section('footer')
@stop

