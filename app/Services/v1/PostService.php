<?php
/**
 * Created by PhpStorm.
 * User: MKH
 * Date: 4/5/2018
 * Time: 4:54 AM
 */

namespace App\Services\v1;
//require('vendor/autoload.php');

use App\Event;
use App\Post;
use App\Postmedia;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Facades\Auth;
use Image;
use App\Comment;
use App\Http\Sockets\MyClass;
use WebSocket\Client;



class PostService
{
    protected $socket;

    public function __construct(MyClass $socket)
    {
        $this->socket = $socket;
    }

    public function createPost($data)
    {
        $post = null;
        if (!$data['body']) {
            return 'not enough input';
        }
        $user = Auth::user();
        $post['body'] = $data['body'];
        $post['user_id'] = $user->id;
        if ($data['club']) {
            $event = Event::where('name', $data['club'])->first();
            if (empty($event)){
                return 'no event with that name';
            }
            $manager = $event->tourManagers;
            if (!$manager->contains($user->id)){
                return 'you are not a manager';
            }
            $post['event_id'] = $event->id;


        } else {
            $post['event_id'] = null;
        }

        if ($data->hasFile('media')) {
//            return "we got your media";
            $file = $data['media'];
//            return 's';
            $post['media_id'] = $this->mediaHandler($file);
        }

        Post::create($post);
        return 'done';
    }


    public function commentPost($comment,$pid){
        if (!$comment['body']){
            return 'not enough inputs';
        }
        $post = Post::find($pid);
        if (empty($post)){
            return 'no post';
        }
        $user = $post->user;
        if ($this->checkPrivateUsers($user)){
            if ($this->checkFallower($user) == false){
                return 'you must follow the user first';
            }
        }
        Comment::create(['body'=>$comment['body'] , 'user_id'=>Auth::user()->id , 'post_id'=>$pid]);
        return true;


    }


    public function showPost($pid){
        $post = Post::find($pid);
        if (empty($post)){
            return 'no post';
        }
        $user = $post->user;
        if ($this->checkPrivateOrBlock($user) == false){
            return 'no access';
        }
        $media_path = null;
        if ($post->media_id){
            $media_path = $this->getImageFoldersName(null,$post->photo->created_at).$post->photo->name;
        }
        $data = [
            'id'            =>$post->id,
            'writer'        =>$post->user->name ,
            'writer_avatar' =>$post->user->avatar_id ? $post->user->avatar->path : null,
            'body'          =>$post->body,
//            'media'         =>$post->media_id ? $post->photo->name : null,
            'media'         =>$media_path,
            'club'          =>$post->event_id ? $post->event->name : null,
            'likes_count'   =>$post->likes ? count($post->likes) : 0,
            'comments_count'=>$post->comments ? count($post->comments) : 0,
            'liked'         =>$post->likes ? $this->usersDatas($post->likes) : null,
            'comments'      =>$post->comments ? $this->commentDatas($post->comments) : null,
        ];
        if ($post->likes->contains(Auth::user()->id)){
            $data['user_like'] = true;
        }else{
            $data['user_like'] = false;
        }
        return $data;

    }

    public function deletePost($pid){
        $post = Post::find($pid);
        if (empty($post)){
            return 'no post';
        }
        $user = Auth::user();

        if ($post->user_id != $user->id){
            return 'no access';
        }
//        return "s";
        if ($post->media_id){
            $this->deleteMedia($post);
        }
        $post->comments()->delete();
        $post->likes()->detach();
        $post->delete();
        return "Post Deleted";
    }

    public function likePost($pid){
        $post = Post::find($pid);
        if (empty($post)){
            return 'no post';
        }
        if ($post->likes->contains(Auth::user()->id)){
            $post->likes()->detach(Auth::user()->id);
            return 'post liked';
        }

        $post->likes()->attach(Auth::user()->id);
        //gathering required data for sending notification
//        $data = [
//            'api_token'=>'0000Server0000',
//            'post_media'=>$post->media_id ? $post->photo->name : null,
//            'post_id'=>$post->id,
//            'user_liked'=>Auth::user()->name,
//            'user_avatar'=>Auth::user()->avatar_id ? Auth::user()->avatar->path : null,
//            'type'=>'like',
//        ];
//        $data = json_encode($data);
//        $this->socket->sendNotification($post->user->id , $data);
//        require('vendor/autoload.php');
        return 'post unLiked';
        $client = new Client("ws://localhost:8083/myclass");
        $client->send("api_token");

        return $client->receive();
    }















    /**
     * MINI functions
     *
     *
     *
     */


    protected function mediaHandler($file){
        $file_original = $_FILES['media'];
        $video_formats = array('video/mp4' , 'video/3gp');
        $image_formats = array('image/png' , 'image/jpg', 'image/jpeg');
        $file_type = $file_original['type'];

        if (in_array($file_type , $image_formats)){
            $file_name = time() . '.' . $file->getClientOriginalName();
            $path_original = storage_path($this->getImageFoldersName('photos/posts/original/') . $file_name);
            $path_thumbnail = storage_path($this->getImageFoldersName('photos/posts/thumbnail/') . $file_name);
            $path_medium = storage_path($this->getImageFoldersName('photos/posts/medium/') . $file_name);
            Image::make($file)->fit(150, 150)->save($path_thumbnail);
            Image::make($file)->fit(300, 300)->save($path_medium);
            Image::make($file)->save($path_original);
            $photo  =  Postmedia::create(['name'=>$file_name]);
            return $photo->id;
        }

        if (in_array($file_type , $video_formats)){

            $filename = time().'.'.$file->getClientOriginalName();

            $path = storage_path('/videos/posts/');
            $file->move($path , $filename);
            $video = Postmedia::create(['name'=>$filename]);
            return $video->id;
        }
        return "we didnt get the image";
    }

    protected function eventAdmin($event){
        $managers = $event->tourManagers;
        if ($managers->contains(Auth::user()->id)){
            return true;
        }else{
            return false;
        }
    }

    protected function checkPrivateUsers($user){
        if ($filter = $user->filter ){
            if ($filter->request == 1){
                return true;
            }
        }
        return false;
    }

    protected function checkFallower($user){
        if ($user->followers->contains(Auth::user()->id)){
            return true;
        }
        return false;
    }

    protected function usersDatas($users){
        $data = null;
        foreach ($users as $user){
            $data[] = [
                'name'=>$user->name ,
                'avatar'=>$user->avatar_id ? $user->avatar->path : null,
            ];
        }
        return $data;
    }

    protected function commentDatas($comments){
        $data = null;
        foreach ($comments as $comment){
            $data[] = [
                'user'=>$comment->user->name,
                'user_avatar'=>$comment->user->avatar_id ? $comment->user->avatar->path : null,
                'body'=>$comment->body,
                'created_at'=>$comment->created_at,

            ];
        }
        return $data;
    }

    protected function deleteMedia($post){
        //TODO:: have buggs
        $video_formats = array('.mp4', '.3gp');
        $image_formats = array('.png' , '.jpg', '.jpeg');
        $media_name = $post->photo->name;
        $media_format = null;
        foreach ($image_formats as $format){
            if (strrpos($media_name , $format) !== False){
//                return $media_name;
                $nameLocation = strrpos($media_name , $format);
                $name_lenght = strlen($media_name);
                $format_lenght = strlen($format);
                if ( $name_lenght- $format_lenght == $nameLocation){
                    $media_format = $format;
                    break;
                };
            }
        }

        foreach ($video_formats as $format){
            if (strrpos($media_name , $format) !== False){
                $nameLocation = strrpos($media_name , $format);
                $name_lenght = strlen($media_name);
                $format_lenght = strlen($format);
                if ( $name_lenght- $format_lenght == $nameLocation){
                    $media_format = $format;
                    break;
                };
            }
        }

        if (in_array($media_format , $video_formats)){
            $path = storage_path('videos/posts/'.$media_name);
            unlink($path);
        }

        if (in_array($media_format , $image_formats)){
            $path_original = storage_path($this->getImageFoldersName('photos/posts/original/').$media_name);
            $path_medium = storage_path($this->getImageFoldersName('photos/posts/medium/').$media_name);
            $path_thumbnail = storage_path($this->getImageFoldersName('photos/posts/thumbnail/').$media_name);
            unlink($path_original);
            unlink($path_medium);
            unlink($path_thumbnail);
        }
        $post->photo->delete();
    }


    protected function checkPrivateOrBlock($user){
        $authUser = Auth::user();
        if ($user->followers->contains($authUser->id)){
            return true;
        }
        if (isset($user->filter) and $user->filter->request==1 ){
            return false;
        }
        if (isset($user->blockedUsers) and  $user->blockedUsers->contains($authUser->id)){
            return false;
        }
        return true;

    }


    protected function getImageFoldersName($directory=null , $date="nothing"){

        if ($date == "nothing"){
            $date = Carbon::now();
        }else{
            $date = Carbon::parse($date);
        }

        if ($directory==null){
            return ('/'.$date->year.'/'.$date->month.'/'.$date->day.'/');
        }

        if (!file_exists(storage_path($directory.'/'.$date->year))){
            mkdir(storage_path($directory.'/'.$date->year));
        }
        if (!file_exists(storage_path($directory.'/'.$date->year.'/'.$date->month))){
            mkdir(storage_path($directory.'/'.$date->year.'/'.$date->month));
        }
        if (!file_exists(storage_path($directory.'/'.$date->year.'/'.$date->month.'/'.$date->day))){
            mkdir(storage_path($directory.'/'.$date->year.'/'.$date->month.'/'.$date->day));
        }
        return ($directory.'/'.$date->year.'/'.$date->month.'/'.$date->day.'/');
    }
}