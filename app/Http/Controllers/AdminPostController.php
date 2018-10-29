<?php

namespace App\Http\Controllers;

use App\Photo;
use App\Post;
use Illuminate\Http\Request;
use Image;
class AdminPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $posts = Post::all();
        foreach ($posts as $post) {
            $post->user;
            $post->photo;
            $post->event;
        }

        return view('admin.post.index',compact('posts'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.post.create');
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

        $post = $request->all();

        if ($file = $request->file('photo')){

            $file_name = time() .''. $file->getClientOriginalName();



            $original_path = storage_path('posts/original/'.$file_name);
            $medium_path = storage_path('posts/medium/'.$file_name);
            $large_path = storage_path('posts/large/'.$file_name);
            $thumbnail_path = storage_path('posts/thumbnail/'.$file_name);
            Image::make($file)->resize(1080, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($large_path);
            Image::make($file)->resize(540, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($medium_path);
            Image::make($file)->resize(150 , null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbnail_path);
            Image::make($file)->save($original_path);

            $photo = Photo::create(['path'=>$file_name]);
            $post['photo_id'] = $photo->id;

        }
        Post::create($post);

        return redirect('admin/post');

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
    public function destroy( $post_id)
    {
        //

        $post = Post::findOrFail($post_id);

        if ($photo = $post->photo){
            $photo = Photo::findOrfail($photo->id);
            $orinal_path = storage_path('/posts/original/'.$photo->path);
            $medium_path = storage_path('/posts/medium/'.$photo->path);
            $large_path = storage_path('/posts/large/'.$photo->path);
            $thumbnail_path = storage_path('/posts/thumbnail/'.$photo->path);



            unlink($orinal_path);
            unlink($medium_path);
            unlink($large_path);
            unlink($thumbnail_path);

            $photo->delete();
        }
        $post->delete();
        return redirect('admin/post');

    }
}
