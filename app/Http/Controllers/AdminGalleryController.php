<?php

namespace App\Http\Controllers;

use App\Photo;
use App\User;
use Illuminate\Http\Request;

class AdminGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();
        foreach ($users as  $user){
            foreach ($user->gallery as $photo){
                $photos[] = $photo;
            }
        }
//        return $photos;
        return view('admin.gallery.index' , compact('users'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //in the future for adding tag feature part
        $photo = Photo::find(1);

        foreach ($photo->PhotoOwners as $user){
            $userid = $user->id;
//            $photo->gallery()->detach($userid);

        }
        return $photo;
//        return redirect('gallery.index') ;
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
        $photo = Photo::find(1);

        foreach ($photo->PhotoOwners as $user){
            $userid = $user->id;
//            $photo->gallery()->detach($userid);
//            unlink('gallery/')
        }

//        return redirect('gallery.index') ;
    }
}
