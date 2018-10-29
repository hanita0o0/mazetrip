<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Type;
use Illuminate\Http\Request;
use Image;

class AdminTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $types = Type::all();
        return view('admin.type.index',compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.type.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //adding cover to type
        if( $file = $request->file('cover')){
            $file_name = time().'.'.$file->getClientOriginalName();
            $path = public_path('type/cover/'.$file_name);
            image::make($file )->fit(1500,500)->save($path);
            $path = public_path('type/cover/thumbnail/'.$file_name);
            image::make($file )->fit(300,100)->save($path);


            $photo = Photo::create(['path'=>$file_name]);
            $request['cover_id'] = $photo->id;
        }
        //adding photo to type
        if( $file = $request->file('avatar')){

            $file_name = time().'.'.$file->getClientOriginalName();
            $path = public_path('type/avatar/'.$file_name);
            image::make($file )->fit(400,400)->save($path);


            $photo = Photo::create(['path'=>$file_name]);
            $request['avatar_id'] = $photo->id;
        }
        //storing the type information
        Type::create($request->all());
        return redirect('admin/type');

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

        $type = Type::findOrFail($id);
        return view('admin.type.edit',compact('type'));
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
        $type = Type::findOrFail($id);

        //updating avatar part
        if ($file = $request->file('avatar')){
            if($type->avatar_id){
                $file_name = $type->avatar->path;
                unlink('type/avatar/'.$file_name);
                $avatar_id =  $type->avatar_id;
                Photo::find($avatar_id)->delete();

            }
            $file_name =time(). '.'. $file->getClientOriginalName();
            $path = public_path('type/avatar/'.$file_name);
            Image::make($file)->fit(400 , 400)->save($path);
            $photo = Photo::create(['path'=>$file_name]);

            $request['avatar_id'] = $photo->id;
        }

        //updating cover part
        if ($file = $request->file('cover')){
            if($type->cover_id){
                $file_name = $type->cover->path;
                unlink('type/cover/'.$file_name);
                unlink('type/cover/thumbnail/'.$file_name);
                $cover_id =  $type->cover_id;
                Photo::find($cover_id)->delete();

            }
            $file_name =time(). '.'. $file->getClientOriginalName();
            $path = public_path('type/cover/'.$file_name);
            $path_thumbnail = public_path('type/cover/thumbnail/'.$file_name);
            Image::make($file)->fit(1500 , 500)->save($path);
            Image::make($file)->fit(300 , 100)->save($path_thumbnail);
            $photo = Photo::create(['path'=>$file_name]);

            $request['cover_id'] = $photo->id;
        }


        $type->update($request->all());
        return redirect('admin/type');
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
        $type = Type::findOrFail($id);
        if($type->avatar_id){
             $file_name = $type->avatar->path;
            unlink('type/avatar/'.$file_name);
            Photo::find($type->avatar_id)->delete();

        }
        if($type->cover_id){
            $file_name = $type->cover->path;
            unlink('type/cover/'.$file_name);
            unlink('type/cover/thumbnail/'.$file_name);
            Photo::find($type->cover_id)->delete();

        }


        $type->delete();
        return redirect('admin/type');
    }
}
