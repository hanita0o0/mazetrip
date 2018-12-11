<?php
/**
 * Created by PhpStorm.
 * User: MKH
 * Date: 5/14/2018
 * Time: 7:56 AM
 */

namespace App\Services\v1;


use App\Event;
use App\Photo;
use App\Post;
use App\Postmedia;
use App\Ticket;
use App\User;
use App\Type;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\build_query;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

class HomeService
{
//    protected $post;

//    public function __construct(PostService $postService)
//    {
//        $this->post = $postService;
//    }

    public function Home($userPostLimits=20 , $userPostSkips=0 , $clubsPostLimits=20 , $clubPostSkips=0){

        $user = Auth::user();
        $clubs = $user->events; // list of clubs subscribes by user
       // $clubId=$user->events->id;
        $club_id=$clubs->pluck('id')->toArray();
        if (empty($clubs)){
            return 0;
        }
        $clubPosts = Post::whereIn('event_id',$club_id)->skip((int)$clubPostSkips)->take((int)$clubsPostLimits)->latest('updated_at')->get();
        // return $clubPosts;

        $data['user_posts'] =$this->getUserPosts((int)$userPostLimits,(int)$userPostSkips);
       $data['club_posts'] =$this->postData($clubPosts);

//        $tickets = Ticket::where('event_id',$clubs->pluck('id'))->whereDate('date','>',date('Y-m-d'))->skip($ticketSkip)->take($ticketLimits)->get();
//        $data['tickets']  = $this->ticketData($tickets);
        //$data=$clubPosts;
        return $data;
    }






    ////////////////////////protocted functions\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


    /**
     * @param $limitPosts
     * @param $skipPosts
     * @param null $limitPostsClubs
     * @param null $skipPostsClubs
     * @return string
     */
    protected function getUserPosts($limitPosts , $skipPosts ){
        if ($limitPosts==null){
            $limitPosts = 20;
        }
        if ($skipPosts == null){
            $skipPosts = 0;
        }
        $user = Auth::user();
        $selfId=$user->id;
        $followings = $user->followers;
        if (empty($followings->first())){
            return null;
        }else {
            //todo:: have to add last activity to user table to check his followings posts
            $userId = $followings->pluck('id')->toArray();
            array_push($userId,$selfId);
            //return $userId;
            $pp = Post::whereIn('user_id', $userId)->skip((int)$skipPosts)->take((int)$limitPosts)->latest('updated_at')->get();
           $posts=$pp->where('event_id','===',null)->orderBy('updated_at', 'DESC')->all();
            return $this->postData($posts);

        }

//        $clubs = $user->events;
//        $clubPosts = Post::where('event_id',$clubs->pluck('id'))->skip($skipPostsClubs)->take($limitPostsClubs)->get();
//        $data['club_posts'] = $this->postData($clubPosts);
//        return Post::where('created_at','>',time())->get();
//        return Carbon::now();
//        return Ticket::find(1)->date;
//        $tickets = Ticket::where('event_id',$clubs->pluck('id'))->whereDate('date','>',date('Y-m-d'))->get();
//        return $tickets->first()->managers;
//        $data['tickets']  = $this->ticketData($tickets);


        // return $data;
    }





    protected function postData($posts){
        $data = null;

        if (isset($posts[0])){
            foreach ($posts as $post) {

                $data[] = [
                    'Pid' => $post->id,
                    'body' => $post->body,
                    'media'=>$post->media_id?$this->getImageFoldersName(null,$post->photo->created_at).$post->photo->name:null,
                    // 'media' => $medias->where('id', $post[]->media_id)->first() ? $this->getImageFoldersName(null,$post->photo->created_at).$medias->where('id', $post->media_id)->first()->name : null,
                    'writer' => $post->user_id?$post->user->name:null,
                    'writer_avatar' =>$post->user->avatar_id? $post->user->avatar->path:null,
                    'created_at' => $post->created_at,
                    'club_name' => $post->event_id ? $post->event->name : null,
                    'likes_count'=>count($post->likes) ,
                    'comment_count'=>count($post->comments),
                    'self_like'=>$post->likes->contains(Auth::user()->id) ? True : False,
                ];
            }
            return $data;
        }
        return $data;

    }

    protected function ticketData($tickets){
        $data1 = array();
        $events = Event::where('id',$tickets->pluck('id'));
        foreach ($tickets as $ticket){
            $users_id = DB::table('ticket_user')->where('ticket_id',$ticket->id)->get();
            $friends=null;
            $data2=array();
//            return $users_id;
            if ($users_id->first()) {
                $users = User::find($users_id->pluck('user_id'));
                foreach ($users as $user) {
                    $data2[] = [
                        'name' => $user->name,
                    ];
                }
            }
//            echo $data2;
//            $data = dd($data2);
//            $data = array_merge($data1,$data2);
            $data1[]=[
                'id'=>$ticket->id,
                'body'=>$ticket->body,
                'avatar_id'=>$ticket->avatar_id ? $ticket->avatar->path : null,
                'date'=>$ticket->date ,
                'end_date'=>$ticket->end_date ,
                'event'=>$ticket->event_id ? $events->where('id',$ticket->event_id) : null,
                'price'=>$ticket->price ,
                'state'=>$ticket->state ? $ticket->State->name : null,
                'city'=>$ticket->city ? $ticket->City->name : null,
                'created_at'=>$ticket->created_at ,
//                'managers'=>$this->ManagersData($ticket->managers),
                'users_numbers'=>count($users_id),
                'limit'=>$ticket->filter ? $ticket->filter->limit : null,
                'users_following_in_ticket'=>$data2,
//                'friends in ticket'=>$this->getFollowersInSameTicket($ticket)
            ];
        }
        return $data1;
    }

    protected function ManagersData($managers){
        $avatars = Photo::where('id',$managers->pluck('avatar_id'))->get();
        $data = null;
        foreach ($managers as $manager){
            $data[]= [
                'name'=>$manager->name,
                'avatar'=>$manager->avatar_id ? $avatars->where('id',$manager->avatar_id) : null,
                'role'=>$manager->pivot->role,
            ];
        }
        return $data;
    }

//    protected function getFollowersInSameTicket($ticket){
//        $users = $ticket->user->pluck('id');
////        return gettype($users->toArray());
//
//        $user = Auth::user();
////        return $ticket->user->pluck('id');
////        return $users;
//        $friends = DB::table('followers')->where(['user_id'=>$users],['follower_id'=>$user])->take('4')->get();
////        return $friends->pluck('id');
//        return $friends;
//        return $friends->pluck('user_id');
//        return User::find($friends->pluck('user_id'));
////        return $frinds->pluck('name');
//    }
    public function getTypes(){
        $types=Type::all();
        $respond=[];
        foreach($types as $type){
            $respond[]=['type_id'=>$type->id,
                       'name'=>$type->name,
                       'image'=>($type->photo?$type->photo->path :null)
            ];
        }
        return $respond;
    }
    public function getClubs($dd,$state,$city,$clubSkip,$clubSuggest){
        $type=explode('-',$dd);
        $suggestion =DB::table('event_type')->whereIn('Type_id',$type)->get();
       $event=Event::find($suggestion->pluck('event_id'));
       $events=$event->where('state',$state)->Where('city',$city)->slice((int)$clubSkip)->take((int)$clubSuggest)->all();
       $respond=[];
       foreach($events as $event){
           $respond[]=['club_name'=>$event->name,
              'club_image'=>$event->avatar?$this->getImageFoldersName(null,$event->avatarImage->created_at).$event->avatarImage->path:null
           ];
       }

       return($respond);
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