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

class HomeService
{
//    protected $post;

//    public function __construct(PostService $postService)
//    {
//        $this->post = $postService;
//    }

    public function Home($userPostLimits=20 , $userPostSkips=0 , $clubsPostLimits=20 , $clubPostSkips=0){

        $user = Auth::user();
        $clubs = $user->events;
        if (empty($clubs)){
            return 0;
        }
        $clubPosts = Post::where('event_id',$clubs->pluck('id'))->skip((int)$clubPostSkips)->take((int)$clubsPostLimits)->get();
        $data['user_posts'] = $this->getUserPosts((int)$userPostLimits,(int)$userPostSkips);
        $data['club_posts'] = $this->postData($clubPosts);
//        $tickets = Ticket::where('event_id',$clubs->pluck('id'))->whereDate('date','>',date('Y-m-d'))->skip($ticketSkip)->take($ticketLimits)->get();
//        $data['tickets']  = $this->ticketData($tickets);
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
        $followings = $user->following;
        if (empty($followings->first())){
            return null;
        }
        //todo:: have to add last activity to user table to check his followings posts
        $posts = Post::where(['user_id'=>$followings->pluck('id') ,'event_id'=>NULL ])->skip($skipPosts)->take($limitPosts)->get();
        $data = $this->postData($posts);


//        $clubs = $user->events;
//        $clubPosts = Post::where('event_id',$clubs->pluck('id'))->skip($skipPostsClubs)->take($limitPostsClubs)->get();
//        $data['club_posts'] = $this->postData($clubPosts);
//        return Post::where('created_at','>',time())->get();
//        return Carbon::now();
//        return Ticket::find(1)->date;
//        $tickets = Ticket::where('event_id',$clubs->pluck('id'))->whereDate('date','>',date('Y-m-d'))->get();
//        return $tickets->first()->managers;
//        $data['tickets']  = $this->ticketData($tickets);


        return $data;
    }




    protected function postData($posts){
        $data = null;
        $pidies = $posts->pluck('media_id');
        $medias = Postmedia::find($pidies);
//        return $posts;

        if (isset($posts[0])){
            foreach ($posts as $post) {
                $writer = $post->user;
                $data[] = [
                    'Pid' => $post->id,
                    'body' => $post->body,
                    'media' => $medias->where('id', $post->media_id)->first() ? $medias->where('id', $post->media_id)->first()->name : null,
                    'writer' => $writer->name,
                    'writer_avatar' => $writer->avatar_id ? $writer->avatar->path : null,
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
        $type=explode(',',$dd);
        $suggestion =DB::table('event_type')->whereIn('Type_id',$type)->get();
       $event=Event::find($suggestion->pluck('event_id'));
       $events=$event->where('state',$state)->Where('city',$city)->slice((int)$clubSkip)->take((int)$clubSuggest)->all();
       $respond=[];
       foreach($events as $event){
           $respond[]=['club_name'=>$event->name,
              'club_image'=>$event->avatar
           ];
       }

       return($respond);
    }
}