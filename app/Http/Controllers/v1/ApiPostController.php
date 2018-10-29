<?php

namespace App\Http\Controllers\v1;

use App\Services\v1\PostService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiPostController extends Controller
{
    //
    protected $post;

    public function __construct(PostService $postService)
    {
        $this->post = $postService;
    }


    public function createPost(Request $data){
        if (!empty($data)) {
            $data = $this->post->createPost($data);
            return response()->json($data);
        }
    }


    public function commentPost(Request $comment , $pid){
        $data = $this->post->commentPost($comment , $pid);
        return response()->json($data);
    }

    public function showPost($pid){
        $data = $this->post->showPost($pid);
        return $data;
    }

    public function deletePost($pid){
        $data = $this->post->deletePost($pid);
        return $data;
    }

    public function likePost($pid){
        $data = $this->post->likePost($pid);
        return response()->json($data);
    }


}
