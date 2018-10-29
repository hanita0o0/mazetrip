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
                                <th>Password</th>
                                <th>activation-no</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>roles</th>
                                <th>edit</th>
                            </tr>
                            </thead>
                            <tbody>



                                @if($user)
                                    @foreach($user as $users)
                                        <tr>
                                        <td> <img src="{{$users->avatar_id ? 'http://localhost/payebahs5.5/storage/users/avatar/thumbnail/'.$users->avatar->path : 'http://via.placeholder.com/50x50'}}" alt="user" width="50" class="round"></td>
                                        <td>{{$users->id}}</td>
                                        <td>{{$users->name ? $users->name : null}}</td>
                                        <td>{{$users->email ? $users->email : null}}</td>
                                        <td>{{$users->password ? $users->password : null}}</td>
                                        <td>{{$users->activation_no ? $users->activation_no : null}}</td>
                                        <td>{{$users->state_id ? $users->state->name : null}}</td>
                                        <td>{{$users->city_id ? $users->city->name : null}}</td>
                                        <td>{{$users->address ? $users->address :null}}</td>
                                        <td>{{$users->phone ? $users->phone : null}}</td>
                                        <td>{{$users->gender ? $users->gender : null}}</td>
                                        <td>{{$users->role_id ? $users->role->name : null}}</td>
                                        <td><a href="{{route('user.edit',$users->id)}}" class="btn waves-effect waves-light btn-info hidden-md-down">update</a></td>

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

