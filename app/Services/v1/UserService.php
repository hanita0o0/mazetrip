<?php
/**
 * Created by PhpStorm.
 * User: hossein
 * Date: 11/7/2017
 * Time: 11:57
 */


namespace  App\Services\v1;

use App\City;
use App\Event;
use App\Photo;
use App\Post;
use App\Requesthandeling;
use App\State;
use App\Type;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Image;
use PhpParser\ErrorHandler\Collecting;
use Snowfire\Beautymail\Beautymail;
use App\EventPhotos;


class UserService
{
    protected $suportedIncludes = [
        'followers' => 'follower',
        'followings' => 'following',
        'events' => 'event'
    ];

/////////////////////////////////checking to see email or name is already exist \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function getExceptionsEmail($parameters)
    {
        if (isset($parameters)) {
            $user = User::where('email', $parameters)->first();
            if (empty($user)) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function getExceptionsName($parameters)
    {
        if (isset($parameters)) {
            $username = User::where('name', $parameters)->first();
            if (empty($username)) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    /**
     * @param $uname
     * @return string
     *
     * debug is done
     */
    public function followUser($uname)
    {
        $user = User::where('name', $uname)->first();
        $authUser = Auth::user();
        if (empty($user)) {
            return 'couldent find the user';
        }
        if ($user->id == $authUser->id) {
            return 'following user';
        }
        if ($authUser->following->contains($user->id)) {
            $authUser->following()->detach($user->id);
            return 'unfollowed user';
        }
        if ($user->filter) {
            if ($user->filter->request) {
                $filter = $user->filter;
                Requesthandeling::create(['user_id' => Auth::user()->id, 'filter_id' => $filter->id, 'status' => 0]);
                return ' request sended for the user';
            }
        }

        $authUser->following()->attach($user->id);
        return 'following user';
    }

    /**
     * @param $uname
     * @return string
     *
     * debug is done
     */
//    public function unFollow($uname)
//    {
//        $user = User::where('name', $uname)->first();
//        $authUser = Auth::user();
//        if (empty($user)) {
//            return 'couldent find the user';
//        }
//        if ($user->id == $authUser->id) {
//            return 'unfollowed';
//        }
////        return $authUser->following;
//        if ($authUser->following->contains($user->id)) {
//            $authUser->following()->detach($user->id);
//            return 'unfollowed';
//        }
//        return 'you are not following this user';
//    }
/////////////////////////////////create new user \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param $request
     * @return int|string
     *
     *
     * debug is done
     */
    public function Create($request)
    {
        $respond=[];
        $avatar_id=null;
        $user = new User();
        if (!$request->username
            or !$request->email
            or !$request->password
            or !$request->state
            or !$request->city
            or !$request->gender
//            or !$request->address
//            or !$request->state
//            or !$request->city
        ) {
            //means not enoughts inputs;
            return 2;
        }
        if (!$request->state){
            $request->state = null;
        }
        $user->name = $request->username;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->activation_no = uniqid();
        $user->api_token = str_random(60);
        $user->gender = $request->gender;
//        $user->city_id = $request->address ? $request->adress : null;
        $user->city_id = $request->city ? $this->getCity($request->city , $request->state) : null;
        $user->state_id = $request->state ? $this->getState($request->state) : null;
        $user->bio = $request->bio ? $request->bio : null;
        $user->name_header = $request->name_header ? $request->name_header : null;
        $user->save();
        if ($request->file('avatar')) {
           $avatar_id= $this->updateAvatar($request, $user->id);
        }

        $respond['username']=$user->name;
//        $respond['email']=$user->email;
        $respond['avatar'] = $avatar_id;
        $respond['api_token']=$user->api_token;
        $respond['reponse']=1;
        return $respond;


    }



    public function showRequests(){
        $user = Auth::user();
        if (empty($user->filter)){
            return null;
        }
        $filter = $user->filter;
        $requests = $filter->requests;
        $users = null;
        foreach ($requests as $request){
            $user = $request->user;
            $users[] =[
                'name'=>$user->name,
                'avatar'=>$user->avatar_id ? $user->avatar->path : null,
            ];
        }
        return $users;

    }


    public function answerToRequests($users){
        $users = explode(',',$users['users']);
        $users = User::where('name',$users)->get();
        $auth_user = Auth::user();
        if ($users->isEmpty()){
            return null;
        }
        $filter =  $auth_user->filter;
        if (empty($filter) or $filter->requests == false){
            return false;
        }
        foreach ($users as $user){
            if ($request = $user->requests->where('filter_id',$filter->id)->first()){
                $request->delete();
                $user->following()->attach($auth_user->id);
            }
        }

        return true;
    }








    public function updateUserInfor(Request $data)
    {
        if (!$data->password or !$data->user) {
            $user = Auth::user();
            if ($user->password == $data->password and $user->password == $data->password2) {
                if ($data->updatePasword) {
                    if ($user->passwrod == $data->updatePassword) {
                        return 'uncorrect password';
                    }
                    //TODO:: create email link or phone token for restarting the password
                }
                if ($data->updateEmail) {
                    if ($this->getExceptionsEmail($data->updateEmail) == 1) {
                        $user->email = $data->updateEmail;
                    }
                }
                if ($data->updateUserHeader) {
                    $user->name_header = $data->updateUserJeader;
                }
                if ($data->updateUserName) {
                    if ($this->getExceptionsName($data->updateUserName) == 1) {
                        $user->name = $data->updateUserName;
                    }
                }
                $user->save();
            }
        }
    }

/////////////////////////////////call when user update avatar \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    public function updateAvatar($request, $user_id)
    {

        $user = User::findOrFail($user_id);

        if ($id = $user->avatar_id) {
            $photo = Photo::find($id);
            $file_name = $photo->path;
            $path_original = storage_path('users/avatar/original/' . $file_name);
            $path_thumbnail = storage_path('users/avatar/thumbnail/' . $file_name);
            $path_medium = storage_path('users/avatar/medium/' . $file_name);
            $path_large = storage_path('users/avatar/large/' . $file_name);

            unlink($path_large);
            unlink($path_thumbnail);
            unlink($path_original);
            unlink($path_medium);
            $photo->delete();
        }

        $file = $request->file('avatar');
        $file_name = time() . '.' . $file->getClientOriginalName();
        $path_original = storage_path('users/avatar/original/' . $file_name);
        $path_thumbnail = storage_path('users/avatar/thumbnail/' . $file_name);
        $path_medium = storage_path('users/avatar/medium/' . $file_name);
        $path_large = storage_path('users/avatar/large/' . $file_name);
        Image::make($file)->fit(150, 150)->save($path_thumbnail);
        Image::make($file)->fit(500, 500)->save($path_medium);
        Image::make($file)->fit(900, 900)->save($path_large);
        Image::make($file)->save($path_original);


        $photo = Photo::create(['path' => $file_name]);
        $user->avatar_id = $photo->id;
        $user->save();
        return $user->avatar_id;

    }

/////////////////////////////////Email UPDATE \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function UpdateEmail( $emailInputs,$user)
    {
        //$user = Auth::user();

        if ($this->getExceptionsEmail($emailInputs->email) == 1) {

            if (!$emailInputs->oldpassword) {
                return 'please enter password';
            }

            if (!( Hash::check($emailInputs->oldpassword,$user->password))) {
                return 'please enter correct password';
            }
            $user->email = $emailInputs->email;
            $user->save();
      return 1;
      }
       return 0;

    }


/////////////////////////////////UserName UPDATE \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function UpdateUserName($username, $user)
    {
        if ($this->getExceptionsName($username) == 1) {
            $user->name = $username;
            $user->save();
            return 1;
        }
        return 0;
    }

/////////////////////////////////Password UPDATE \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function UpdatePassword($newPassword, $oldPassword, $user)
    {
       if( Hash::check( $oldPassword,$user->password ) ){;

            $user->password = $newPassword;
            if ($user->save()) {
                return 1;
            }
        } else {
            return 0;
        }

    }

/////////////////////////////////City and State UPDATE \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function UpdateCity($city, $user)
    {
        if(!($user->city_id == $city)) {
            $user->city_id = $city;
            if ($user->save()) {
                return 1;
            } else {
                return 0;
            }
        }
        return 0;
    }

    public function UpdateState($state, $user)
    {
        if(!($user->state_id == $state)) {
            $user->state_id = $state;
            if ($user->save()) {
                return 1;
            } else {
                return 0;
            }
        }return 0;
    }
 public function UpdateGender($gender, $user)
 {
     if(!($user->gender ==$gender)) {
         $user->gender =$gender;
         if ($user->save()) {
             return 1;
         } else {
             return 0;
         }
     }return 0;

 }
 public function UpdateBio($bio,$user){
     if(!($user->bio ==$bio)) {
         $user->bio =$bio;
         if ($user->save()) {
             return 1;
         } else {
             return 0;
         }
     }return 0;
 }
 public function UpdateNameHeader($name_header,$user){
     if(!($user->name_header ==$name_header)) {
         $user->name_header =$name_header;
         if ($user->save()) {
             return 1;
         } else {
             return 0;
         }
     }return 0;
 }
    public function UpdatePhone($phone,$user){
        if(!($user->phone ==$phone)) {
            $user->phone =$phone;
            if ($user->save()) {
                return 1;
            } else {
                return 0;
            }
        }return 0;
    }

    ///////////////////////////////// Login \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function login($inputs)
    {
        if (isset($inputs['email'])) {
            $user_email = User::where('email', $inputs['email'])->first();
            if (empty($user_email)){
//                return "not found";
                return 0;
            }

            if (isset($user_email) and $this->getPassword($user_email->password , $inputs['password'])) {
                $user =  $user_email;
            } else {
                return 2;
            }
        }
        if (isset($inputs['user_name'])) {
            $user_name = User::where('name', $inputs['user_name'])->first();
            if (empty($user_name)) {
                return 0;
            };
            if (isset($user_name) and $this->getPassword($user_name->password,$inputs['password'])){
                $user = $user_name;
            } else {
                return 2;
            }
        }

        if (isset($inputs['phone'])) {
            $user_phone = User::where('phone',$inputs['phone'])->first();
            if (empty($user_phone)){
                return 0;
            }
            if (isset($user_phone) and $this->getPassword($user_phone->password , $inputs['password'])) {
                $user =  $user_phone;
//                return 2;
            } else {
                return 2;
            }
        }

        $data['token'] = $user->api_token;
        $data['userName'] = $user->name;
        $data['avatar'] = $user->avatar_id ? $user->avatar->path : null;
        return $data;

    }

    /**
     * /////////////////////////////////returning User Profile \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
     */
    public function UserData($user, $post_limits = 10, $api_token = null)
    {
        //todo :: debug count of $user-followers
        //todo :: post_limits
        $api_user = User::where('api_token', $api_token)->first();
        $data = [];
        $data['username'] = $user->name;
        $data['followers_number'] = count($user->followers);
        $data['following_number'] = count($user->following);
//        $data['followed_clubs_number'] = count($user->events);
        $data['posts_numbers'] = count($user->posts);
        $data['name_header'] = $user->name_header ? $user->name_header : null;
        $data['avatar'] = $user->avatar_id ? $user->avatar->path : 'no image';
        $data['bio'] = $user->bio;
        if ($filter = $user->filter) {
            if (isset($api_user)) {
                if ($user->followers->contains($api_user->id)) {
                    $filter->request = 0;
                }
            }
            if ($filter->request == 1) {

                $data['posts'] = 'profile is private';
                $data['followed_clubs'] = 'profile is private';
                $data['users_clubs'] = 'profile is private';

            } else {
                $data['posts'] = $this->PostData($user->id, $post_limits);
                $events = $user->events;
                $clubs = array();
                foreach ($events as $event) {
                    $clubs[] = $this->eventDataStruct($event);
                }
                $data['followed_clubs'] = $clubs;
                $userClubs = $user->toureManagers;
                $clubs = array();
                foreach ($userClubs as $userClub){
                    $clubs[] = $this->eventDataStruct($userClub);
                }
                $data['users_clubs'] = $clubs;
            }
        } else {
            $data['posts'] = $this->PostData($user->id, $post_limits);
            $events = $user->events;
            $clubs = array();
            foreach ($events as $event) {
                $clubs[] = $this->eventDataStruct($event);
            }
            $data['followed_clubs'] = $clubs;
            $userClubs = $user->toureManagers;
            $clubs = array();
            foreach ($userClubs as $userClub){
                $clubs[] = $this->eventDataStruct($userClub);
            }
            $data['users_clubs'] = $clubs;
        }
        if (isset($data['users_clubs'])) {
            $data['following_clubs_number'] = count($user->events) - count($data['users_clubs']);
        }
        if ($api_user){
            $data['login_user_follow_this_account'] = $api_user->following->contains($user) ? true : false;
            $data['this_user_follow_login_user']=$api_user->followers->contains($user) ? true : false;
        }

        return $data;
    }


//////////////////////////////////////search User\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function searchUser($userName)
    {
//        $data = new Collecting();
//        $data1 = new Collecting();
//        $data2 = new Collecting();
        $users1 = User::where('name','like','%'.$userName.'%')->get();
        $users2= User::where('name_header','like','%'.$userName.'%')->get();
        $users = $users1->merge($users2);

        foreach ($users as $user){
            $datas[] = [
                'user_name' =>$user->name,
                'header_name'=>$user->header_name ? $user->header_name : null,
                'avatar'=>$user->avatar_id ? $user->avatar->path : null,
                'name_header'=>$user->name_header,
            ];
        }
        return $datas;

    }




//////////////////////////////////////returning posts \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function PostData($user_id=[] , $limit =20 ){
        //todo :: debug
        $posts = Post::where('user_id',$user_id)->take($limit)->orderBy('created_at','desc')->get();

        $data = [];
        foreach ($posts as $post){
            if (isset($post->event_id)){
                continue;
            }
            $data[] = [
                'user'=>$post->user->name,
                'id'=>$post->id,
                'body'=>$post->body,
                'event'=>$post->event_id ? $post->event->name : null,
                'photo'=>$post->media_id ? $this->getImageFoldersName(null,$post->created_at).$post->photo->name : null,
                'created_at'=>$post->created_at,
                'likes'=>$post->likes ? count($post->likes) : 0,
                'comments'=>$post->comments ? count($post->comments) : 0,
            ];
            if (count($data)>$limit){
                break;
            }
        }
        return $data;
    }



//////////////////////////////////////returning events and followerd ones \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function showUserFollowers($user,$limit , $skip){
        if ($skip == null){
            $skip = 0;
        }
        if ($limit == null){
            $limit = 20;
        }
        $data = null;
        $user = User::where('name',$user)->first();
        if (empty($user)){
            return 'no user';
        }
        $user_followers = $user->followers()->skip($skip)->take($limit)->orderBy('created_at','desc')->get();
        $user_followings = $user->following;
        $avatars = $user->followers->pluck('avatar_id');
        $avatars = Photo::find($avatars);
        foreach ($user_followers as $follower){
            $data[] = [
                'name'=>$follower->name,
                'avatar' =>$follower->avatar_id ? $avatars->where('id',$follower->avatar_id)->first()->path : null,
                'is_followed_by_login_user' => $user_followings->contains($follower) ? true : false,
            ];
        }
        return $data;
    }

    public function showUserFollowing($user , $limit, $skip ){
        if ($skip == null){
            $skip = 0;
        }
        if ($limit == null){
            $limit = 20;
        }
        $data = null;
        $user = User::where('name',$user)->first();
        if (empty($user)){
            return 'no user';
        }
        $user_followings = $user->following()->skip($skip)->take($limit)->orderBy('created_at','desc')->get();
        $user_followers = $user->followers;
        $avatars = $user->following->pluck('avatar_id');
        $avatars = Photo::find($avatars);
        foreach ($user_followings as $following){
            $data[] = [
                'name'=>$following->name,
                'avatar' =>$following->avatar_id ? $avatars->where('id',$following->avatar_id)->first()->path : null,
                'is_followed_by_login_user' => $user_followers->contains($following) ? true : false,
            ];
        }
        return $data;

    }


    public function showClubFollower($user){
        $data = null;
        if ($user = User::where('name',$user)->first()){
            $events = $user->events;
            foreach ($events as $event){
                $data[] =[
                    'name'=>$event->name,
                    'header'=>$event->header,
                    'avatar'=>$event->avatar ? $event->avatarImage->path : null,
                ];
            }
        }
        return $data;
    }


    public function selfProfileShow($posts_limits = 10  , $user = null){
//        return 'you are in self profile';
        if ($user == null){
            $user = Auth::user();
        }
        $data = $this->userData($user ,$posts_limits);
        if ($data['users_clubs']){
            unset($data['users_clubs']);
        }
        if($userOwnEvents = $user->toureManagers){
            $userClubs = array();
            foreach ($userOwnEvents as $event){
                if ($event->tourManagers){
                    $managers = array();
                    foreach ($event->tourManagers as $manager){
                        $managers[] = [
                            'user' => $manager->name,
                            'user_avatar'=>$manager->avatar_id ? $manager->avatar->path : null,
                            'role' => $manager->pivot->role ? $manager->pivot->role : null ,
                        ];
                    }
                }
                if ($event->is_active == 0){
                    continue;
                }
                $userClubs[] = [
                    'club_name'=> $event->name,
                    'avatar'=>$event->avatar ? $event->avatarImage->path : null,
                    'header'=> $event->header ? $event->header : null,
                    'about'=> $event->about ? $event->about : null,
                    'about_team'=> $event->about_team ? $event->about_team : null,
                    'type'=>$event->type_id ? $event->type->name : null,
                    'state'=>$event->state ? $event->State->name : null,
                    'city'=>$event->city ? $event->City->name : null,
                    'time'=>$event->created_at,
                    'private'=>$event->Filter ? $event->Filter->request: null,
                    'hide_event'=>$event->Filter ? $event->Filter->hide_event : null,
                    'gender'=>$event->Filter ? $event->Filter->gender : null,
                    'managers'=>$managers ? $managers : null,
                ];
            }
        }

        $data['clubs'] = $userClubs;
        return $data;

    }
    //.show edit profile
       public function selfProfileShowEdit(){

               $user = Auth::user();
              $data['id']= $user->id;
              $data['username']= $user->name;
              $data['name_header']= $user->name_header;
              $data['email']= $user->email;
              $data['gender']=$user->gender;
              $data['phone']=$user->phone;
              $data['bio']=$user->bio;
              $data['avatar']=$user->avatar ?$user->avatar->path : null;
              $data['state']=['id'=>$user->state_id,'name'=>$user->state->name];
              $data['city']=['id'=>$user->city_id,'name'=>$user->city->name];
              return $data;

       }

    //todo:: have to create tickets to event manager in seperate


////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * writing mini methods
     *
     */

    protected function getState($state){
        $state = State::where('name',$state)->first();
        if (empty($state)){
            return null;
        }
        return $state->id;
    }

    protected function getCity($city , $state=null){
        $state = State::where('name',$state)->first();
        if (empty($state)){
            return null;
        }
        $city_db = City::where(['name'=>$city , 'state_id'=>$state->id] )->first();
        if (empty($city_db)){

            $city = City::create(['name'=>$city,'state_id'=>$state->id]);
            return $city->id;
        }else {
            return $city_db->id;
        }
    }


    protected function EventData($user){
        $data = [];
        return 'hi';
        foreach ($user->events()->orderBy('subscribers.created_at', 'desc')->get() as $event){
            if ($event->is_active == 1) {
                $data[] = [
                    'event_name' => $event->name,
                    'followers' => count($event->subscribers),
                    'photo' => $event->avatar ? $event->avatarImage->path : null,
                    'type' => $event->type ? Type::find($event->type) : null,

                ];
            }
        }
//        foreach ($event = $user->toureMnagers)
        return $data;

    }

    protected function EventDataSelfUser($managerInfo){
        $data = [];
        //todo : have to figure it out :D
        return 'salam';
        foreach ($managerInfo->orderBy('subscribers.created_at', 'desc')->get() as $event){
            if ($event->is_active == 1) {
                $data[] = [
                    'event_name' => $event->name,
                    'followers' => count($event->subscribers),
                    'photo' => $event->avatar ? $event->avatarImage->path : null,
                    'type' => $event->type ? Type::find($event->type) : null,

                ];
            }
        }
        return $data;

    }


    protected function FollowingData($user){
        foreach ($user->following as $following){
            $data[] =[
                'name'=>$following->name,
                'link'=>"http://localhost/payebahs5.5/public/api/v1/user/".$following->name,
            ];
        }
        return $data;
    }

    protected function FollowersData($user){
        $data = null;
        $authUser = Auth::user();
        if ($user->followers()->contains($authUser->id)){
            $data[] = $user->followers()->pluck('name');
        }
        return $data;

    }


    protected function getArrays($parameters){
        $includeParams = explode(',',$parameters['include']);
        $array = array_intersect($this->suportedIncludes , $includeParams);
        $keys = array_keys($array);
        return $keys;
    }

    public function getBlockUser($user){
        $user_auth = Auth::user();
        if ($user_auth->blockedUsers->contains($user->id)){
            return true;
        }
        return false;
    }

    public function getBlockedUsers(){
        $user = Auth::user();
        return $user->blockedUsers;
    }

    public function blockUser($user){
        $user_auth = Auth::user();
        $user_auth->blockedUsers()->attach($user->id);
    }

    public function unblockUser($user){
        $user_auth = Auth::user();
        if ($this->getBlockUser($user)) {

            $user_auth->blockedusers()->detach($user);
            return true;
        }
        return false;
    }


    protected function eventDataStruct($event){
        $event = [
            'club_name'=>$event->name,
            'type'=>$event->type_id ? $event->type->name : null,
            'cover'=>$event->avatar ? $this->getImageFoldersName(null,$event->avatarImage->created_at).$event->avatarImage->path : null,
            'state'=>$event->state ? $event->State->name : null,
            'city'=>$event->city ? $event->City->name :null,

        ];
        return $event;
    }

    public function resetPassword($user){
        $table = [
            'token'=>uniqid(rand(),true),
            'email'=>$user->email,
            'created_at'=>Carbon::now(),
        ];
        DB::table('password_resets')->where('email',$user->email)->delete();
        DB::table('password_resets')->insert($table);
        $token = DB::table('password_resets')->where('email',$user->email)->latest()->first();
        $this->sendMail('forgetPassword', $user ,$token);
    }

    public function updateForgetPassword($user , $request){
        $user['password'] = $request;
        $user['api_token'] = bin2hex(random_bytes(32));
        $user['name'] = 'hossein_update';
        $user->save();
        return $user->password;
    }

    protected function sendMail($type , $user , $token){

        if ($type == 'forgetPassword'){
            $beautymail = app()->make(Beautymail::class);
            $userNmae = $user->name;
            $beautymail->send('email.forgetPasswordMail', ['username'=>$userNmae , 'token'=>$token->token], function($message) use ($user) {
                $name = $user->name;
                $message
                    ->from('hossein_tafakh@example.com')
                    ->to($user->email, $name)
                    ->subject('Restart Password');
            });
        }

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


    protected function getPassword($password , $input){
        if (Hash::check($input , $password)){
            return true;
        }
        return false;
    }

}


