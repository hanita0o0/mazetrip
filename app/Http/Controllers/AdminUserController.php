<?php

namespace App\Http\Controllers;

use App\City;
use App\Photo;
use App\Role;
use App\State;
use App\User;
use Image;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = user::all();
        return view('admin.user.index',compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $role = Role::pluck('name','id')->all();
        $state = State::pluck('name','id')->all();
        return view('admin.user.create',compact('role','state'));

//        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function store(Request $request)
    {
        //
        $user = $request->all();


        // if user had image
        if( $file = $request->file('avatar')){

            $file_name = time() . '.'. $file->getClientOriginalName();
            $path_original = storage_path('users/avatar/original/'.$file_name);
            $path_thumbnail = storage_path('users/avatar/thumbnail/'.$file_name);
            $path_medium = storage_path('users/avatar/medium/'.$file_name);
            $path_large = storage_path('users/avatar/large/'.$file_name);
            Image::make($file)->fit(150 , 150)->save($path_thumbnail);
            Image::make($file)->fit(500 , 500)->save($path_medium);
            Image::make($file)->fit(900 , 900)->save($path_large);
            Image::make($file)->save($path_original);



            $photo = Photo::create(['path'=>$file_name]);
            $user['avatar_id'] = $photo->id;
        }

        if( $request->file('cover')){
            $file = $request->file('cover');
            $file_name = time().'.' . $file->getClientOriginalName();
            $location = public_path('user/cover/'. $file_name);
            Image::make($file)->fit(1500 , 500 )->save($location);
            $location = public_path('user/cover/thumbnail/'. $file_name);
            Image::make($file)->fit(300 , 100 )->save($location);


            $photo = Photo::create(['path'=>$file_name]);
            $user['cover_id'] = $photo->id;
        }


        $newcity = $user['city'];
                $city   = city::where('name',$newcity)->first();

        //admin city name
        if(!$city){
            $city = City::create(['name'=>$newcity , 'state_id'=>$request['state_id']]);
            $user['city_id']=$city->id;
            $city['state_id']= $request['state_id'];
        }else {
            $user['city_id']  = $city['id'];
        }
        $user['activation_no'] =uniqid();
//        return $user;
        User::create($user);


        //redirectin to the admin user

        return redirect ('admin/user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $role = Role::pluck('name','id')->all();
        $state = State::pluck('name','id')->all();
        $city = City::pluck('name','id')->all();
        $user =User::findOrFail($id);
        return view('admin.user.edit' , compact('user','role','state','city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = User::findOrFail($id);

        if($request['city']){
            $city = city::where('name',$request['city'])->first();
            if($city){
                $user['city_id'] = $city->id;
            }else{
                $city = City::create(['name'=>$request['city'] , 'state_id'=>$request['state_id']]);
                $user['city_id'] = $city->id;
            }
        }





        if($request->file('avatar')){
            $file = $request->file('avatar');
            //checking and deleting the old picture
            if($user->avatar_id){
                $id = $user->avatar_id;
                $photo = Photo::find($id);
                $name = $photo->path;
                unlink('user/avatar/'.$name);
                $photo->delete();
            }

            $file_name = time() . '.' . $file->getClientOriginalName();
            $location = public_path('user/avatar/'. $file_name);
            Image::make($file)->fit(400 , 400 )->save($location);


            $photo = Photo::create(['path'=>$file_name]);
            $user['avatar_id'] = $photo->id;
        }
        if( $request->file('cover')){
                    $file = $request->file('cover');
                    //checking and deleting the old picture
                    if($user->cover_id){
                        $id = $user->cover_id;
                        $photo = Photo::find($id);
                        $name = $photo->path;
                        unlink('user/cover/'.$name);
                        unlink('user/cover/thumbnail/'.$name);
                        $photo->delete();
                    }

                    $file_name = time().'.' . $file->getClientOriginalName();
                    $location = public_path('user/cover/'. $file_name);
                    Image::make($file)->fit(1500 , 500 )->save($location);
                    $location = public_path('user/cover/thumbnail/'. $file_name);
                    Image::make($file)->fit(300 , 100 )->save($location);


                    $photo = Photo::create(['path'=>$file_name]);
                    $user['cover_id'] = $photo->id;
                }

        $user->update($request->all());
        return redirect('admin/user');
//
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::findOrFail($id);
        if($id = $user->avatar_id){
            $photo = Photo::find($id);
            $name = $photo->path;
            unlink('user/avatar/'.$name);
            $photo->delete();
        }
        if($user->cover_id){
            $id = $user->cover_id;
            $photo = Photo::find($id);
            $name = $photo->path;
            unlink('user/cover/'.$name);
            unlink('user/cover/thumbnail/'.$name);
            $photo->delete();
        }

        $user->delete();
        return  redirect('admin/user');
    }
}
