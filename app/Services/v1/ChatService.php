<?php
/**
 * Created by PhpStorm.
 * User: hossein
 * Date: 11/28/2017
 * Time: 18:14
 */

namespace App\Services\v1;

use App\Ban;
use App\Chat;
use App\Chatgroup;
use App\Message;
use App\Messagephotos;
use App\User;
use GuzzleHttp\Psr7\Request;
use function GuzzleHttp\Psr7\uri_for;
use Image;
use Illuminate\Support\Facades\Auth;

class ChatService
{


    /**
     * @return array|null
     * debug is done
     */
    public function showChats()
    {
        $user = Auth::user();

        $chats = null;

        foreach ($user->events as $event){
            $last_seen=$event->subscribers($user->id)->first()->pivot->updated_at->format('m/d/Y h:i:s');
            $chats[] = [
                'chat_id'=>$event->chat->id,
                'chats_name'=>$event->name,
                'chat_type'=>'event_chat',
                'last_seen'=>$last_seen,
                'messages'=>$this->messages($event->chat),
            ];
        }
//        return $user->chatgroups->whereNotIn('name','salam2');
        foreach ( $user->chatgroups->whereNotIn('information','private Chat') as $chatgroup ){
            $last_seen=$chatgroup->members($user->id)->first()->pivot->updated_at->format('m/d/Y h:i:s');
            $chats[]=[
                'chat_id'=>$chatgroup->chat->id,
                'chats_name'=>$chatgroup->name,
                'chat_type'=>'group_chat',
                'last_seen'=>$last_seen,
                'messages'=>$this->messages($chatgroup->chat)
            ];
        }
        foreach ($user->chatgroups->where('information','private Chat') as $privatechat){
            $last_seen=$chatgroup->members($user->id)->first()->pivot->updated_at->format('m/d/Y h:i:s');
            $chats[]=[
                'chat_id'=>$privatechat->chat->id,
                'chats_name'=>$privatechat->name,
                'chat_type'=>'private_chat',
                'last_seen'=>$last_seen,
                'messages'=>$this->messages($privatechat->chat)
            ];
        }
        return $chats;
    }





//    public function showChatInsideHomePage($chat_id ,$skip = 0 , $take=100){
//        $user = Auth::user();
//        if ($chat = Chat::find($chat_id)) {
//            if ($chat->chatable_type== 'App\Event' and !$chat->chatable->subscribers->contains($user->id)){
//                return 0;
//            }elseif($chat->chatable_type == 'App\Chatgroup' and !($chat->chatable->members->contains($user->id) or $chat->chatable->admins->contains($user->id))){
//                return 0;
//            }else {
//                $data = $this->messages($chat, $take, $skip);
//                return $data;
//            }
//        }else{
//            return 0;
//        }
//    }


    /**
     * @param $chatname
     * @return array|string
     *
     *
     * debug done
     */


    public function chatMembers($chatname){

        $members = array();
        if ($chat = Chat::find($chatname)) {
            $chat_type = $chat->chatable_type;
            if ($this->chatAuth($chat) == 0){
                return 'access denied';
            }
            if ($chat_type == 'App\Event') {
                $event = $chat->chatable;
                foreach ($event->subscribers as $user) {
                    $members[] = [
                        'name' => $user->name,
                        'avatar' => $user->avatar_id ? $user->avatar->path : null,
                    ];
                }

                foreach ($event->tourManagers as $manager) {
                    $admins[] = [
                        'name' => $manager->name,
                        'avatar' => $manager->avatar_id ? $manager->avatar->path : null,
                        'admin' => 'admins'
                    ];
                }
                $members = array_merge($admins, $members);

            } else if ($chat_type == 'App\Chatgroup') {

                $chatGroup = $chat->chatable;

                foreach ($chatGroup->members as $user) {

                    $members[] = [
                        'name' => $user->name,
                        'avatar' => $user->avatar_id ? $user->avatar->path : null,
                    ];
                }

            }

            return $members;
        }else{
            return 'no chat';
        }
    }

    /**
     * @param $chat_id
     * @param int $limit
     * @param int $skip
     * @return array|string
     *
     *
     *
     * debug done
     */
    public function showMessages($chat_id , $limit = 100 , $skip =0 ){

        $chat = $this->checkChat($chat_id);
        if ($chat == null){
            return 'no chat';
        }
        if ($this->chatAuth($chat) == 0){
            return 'access denied';
        }
//        return 1;
        $data = $this->messages($chat , $limit,$skip);
        return $data;
    }


    /**
     * @param $message
     * @return int|string
     *
     *
     *debuged
     */
    public function sendMessage($message){
        $chat = Chat::find($message['chat_id']);
        if (empty($chat)){
            return 'no chat with that id';
        }
        if (empty($message['parent_id'])){
            $message['parent_id']=null;
        }
        if ($this->chatAuth($chat) == 1){
            $data = [
                'content'=>$message['content'],
                'user_id'=>Auth::user()->id,
                'parent_id'=>$message['parent_id'] ? $message['parent_id'] : null,
                'chat_id'=>$chat->id,
            ];
            if($message->hasFile('photo')){
                $id = $this->photoHandle($message->file('photo'));
                $message->messagephoto_id = $id;
            }
            if (Message::create($data)) {
                return true;
            }
        }else{
            return 'no access to chatroom';
        }
        return false;
    }

    /**
     * @param $user
     * @param $content
     * @return bool|string
     *
     *
     *
     * tested and debuged
     */
    public function sendDM($user , $content){
        $authuser = Auth::user();
        $content->photo_id = null;
        $user = User::where('name' , $user)->first();
        if (empty($user)){
            return "no user with this username";
        }

        if (empty($chatgroup = $this->checkPrivateMessageChatroom($user))){


            //create new chatroom
//            if ()
            $chatroomName= $this->getPrivateChangeName($authuser , $user);
            $chatgroup = Chatgroup::create(['name' => $chatroomName, 'information' => 'private chat']);
            $chatgroup->members()->attach($authuser->id);
            $chatgroup->members()->attach($user->id);
            Chat::create(['chatable_id' => $chatgroup->id, 'chatable_type' => 'App\Chatgroup']);
        }
        $chat_id = $chatgroup->chat->id;
        if ($content->hasFile('photo')){
            $content->photo_id = $this->photoHandle($content->file('photo'));
        }
        Message::create([
            'content'=>$content->content,
            'chat_id'=>$chat_id,
            'user_id'=>$authuser->id,
            'parent_id'=>$content->parent_id ? $content->parent_id : null,
            'messagephoto_id'=>$content->photo_id
        ]);
        return true;

    }


    /**
     * @param $id
     * @return bool
     *
     *
     * debug done
     */

    public function deleteTheMessage($id){
        $message = Message::find($id);
        if (empty($message)){
            return false;
        }
        $chat = $message->chat;
        $user = Auth::user();
        if ($message->user_id == $user->id){
            $message->active = 0;
            $message->update();
            return true;
        }
        $chat_type = $this->getChatType($chat);
        if ($this->chatAuth($chat)){
            if ($chat_type == "group"){

                if ($chat->chatable->members->find($user->id)->pivot->admin == true){
                    $message->active = 0;
                    $message->update();
                    return true;
                }
            }
            if ($chat_type == "event"){
                $event = $chat->chatable;
                if ($event->tourManagers->contains($user->id)){
                    $message->active = 0;
                    $message->update();
                    return true;
                }

            }
        }

        return false;

    }


    //edit chatroom

    /**
     * @param $data
     * @param $chat_id
     * @return bool|string
     * done debuging
     */
    public function editChatroom($data , $chat_id){
//        return $data;
        if (empty($chat_id) or empty($data['name']) or (strpos($data['name'] , '{') !== false)){
            return 'name';
        }
        if ($data['information']=='private chat'){
            return 'information';
        }

        $chat = Chat::find($chat_id);
        if (empty($chat)){
            return 'no chat';
        }
//        $chat = $chat->chatable;
//        return $chat;
//        return $this->getChatType($chat);
        if ($this->getChatType($chat) == 'group'){
            if ($this->chatAuth($chat)){
                if (Chatgroup::where('name',$data->name)->first()){
                    return 'name exist already';
                }
                $chatgroup = $chat->chatable;
                if ($chatgroup->update($data->all())){
                    return true;
                }
            }
        }
        return false;
    }


//create group chat

    /**
     * @param $data
     * @return bool|string
     *
     * debuged
     */

    public function createChatRoom($data){
//        return $data;
        if (empty($data->name) or empty($data->information) or (strpos($data->name , '{') !== false) or ($data->information == "private chat")){
            return 'salam';
        }
        if ($this->chatExistence($data->name)){
            $chatroom = Chatgroup::create(['name'=>$data->name , 'information'=>$data->information]);
            $chat = Chat::create(['chatable_id'=>$chatroom->id , 'chatable_type'=>'App\Chatgroup']);
            $chat = $chat->chatable;
            $chat->members()->attach(Auth::user()->id,['admin'=>1]);
            return true;
        }
        return false;
    }
//// add memebers to chatroom

    /**
     * @param $user
     * @param $chat_id
     * @return bool|string
     *
     * debug done
     */
    public function addMember($user , $chat_id)
    {
        if ($user = $this->checkUser($user)) {
            if ($chat = $this->checkChat($chat_id)) {
                if ($this->chatAuth($chat)) {
                    if ($this->checkBanChats($user,$chat)){

                        //todo: have to work on bans and add user_id to the database .
                        return 'salam';
                    }
                    if ($this->getChatType($chat)=='private'){
                        return 'its a private chat';
                    }
                    $chat->chatable->members()->attach($user->id);
                    return true;
                }
                return 'no access to chatroom';
            }
            return 'no chatroom';
        }

        return 'no user';
    }
///

    /**
     * @param $user
     * @param $chat_id
     * @return bool
     *
     *
     * debug done
     */
    public function kickUserChat($user , $chat_id){
        $user = $this->checkUser($user);
        if (empty($user)){
            return false;
        }


        if ($chat = $this->checkChat($chat_id)){
            if ($chat->chatable->members()->where(['user_id'=>Auth::user()->id,'admin'=>true])->first() or $user == Auth::user()){
                $chat->chatable->members()->detach($user);
                if (count($chat->chatable->members) == 0){
                    $chat->chatable->delete();
                    $chat->delete();
                    return true;
                }
                return true;


            }
        }
        return false;
    }

    /**
     * @param $user
     * @return bool
     *
     *
     *
     */
    public function banUser($user){
        if ($user = $this->checkUser($user)) {
            if (!$this->checkBanUser($user->id)) {
                Ban::create(['banable_type'=>'App\User','banable_id'=>$user->id]);
                return true;
            }
        }
        return false;
    }

    public function banUserChat($chat_id , $user){
        if ($chat = $this->checkChat($chat_id)){
            $auth = Auth::user();
            if ($user = $this->checkUser($user)) {
                if ($chat->members()->where(['admin' => '1', 'user_id' => $auth->id])->first()) {
                    $chat->blockedUsers()->attach($user->id);
                    return true;
                }
            }
        }
        return false;
    }

    ///////////////////////////////mini methods\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    ///
    ///

    protected function checkBanUser($user_id){
        $user = Auth::user();
        if ($user->blockedUsers()->contais($user_id)){
            return true;
        }
        return false;
    }

    protected function checkBanChats($user , $chat){
        if ($chat->blockedUsers()->contains($user->id)){
            return true;
        }
        return false;
    }

    protected function checkChat($chat_id){
        if ($chat = Chat::find($chat_id)){
            return $chat;
        }
        return null;
    }

    protected function checkUser($user_name){
        if ($user = User::where('name' , $user_name)->first()){
            return $user;
        }
        return null;
    }
    protected function messages($chat , $limit=1,$skip=0){
        $data= array();
        foreach ($chat->messages()->where('active',1)->take($limit)->skip($skip)->get() as $message){
            $user = $message->writer;
            $data[] =[
                'id'=>$message->id,
                'user'=>$user->name,
                'avatar'=>$user->avatar_id ? $user->avatar->path : null,
                'content'=>$message->content,
                'photo'=>$message->messagephoto_id ? $message->photo->path : null,
                'parent_id'=>$message->parent_id,
                'date'=>$message->created_at->format('m/d/Y h:i:s')
            ];
        }

        return $data;

    }

    protected function chatExistence($chat_name){
        $chat  = Chatgroup::where('name' , $chat_name)->first();

        if (empty($chat)){
            return true;
        }
        return false;
    }


    protected function chatAuth($chat){
        $user = Auth::user();
        $chat_type = $chat->chatable_type;

        if ($chat_type == 'App\Event') {
            $event = $chat->chatable;
            if (!$event->subscribers->contains($user->id) and !$event->tourManagers->contains($user->id)) {
                return 0;
            }
        }else if ($chat_type == 'App\Chatgroup') {
            $chatGroup = $chat->chatable;
            if (!$chatGroup->members->contains($user->id)) {
                return 0;
            }
        }

        return 1;
    }

    protected function getChatType($chat){
        if (empty($chat)){
            return null;
        }
        $chatable = $chat->chatable_type;
        if ($chatable == 'App\Event'){
            return "event";
        }else if ($chatable == 'App\Chatgroup'){
            if ($chat->information == "private Chat"){
                return "private";
            }
            return "group";
        }
        return null;
    }

    public  function photoHandle($file){
//        if (empty($file)){
//            return null;
//        }
        $file_name = time() . '.'. $file->getClientOriginalName();
        $path_original = storage_path('photos/chat/large/'.$file_name);
        Image::make($file)->save($path_original);
        $photo = Messagephotos::create(['path'=>$file_name]);
        return $photo->id;
    }


    /////////////////////////////////////////////////

    //TODO:have to create send message and see message sepretly and filter private message

    protected function getPrivateChangeName($user1 , $user2){
        $users = array(
            $user1->id =>$user1->name,
            $user2->id =>$user2->name,
        );
        ksort($users);
        return json_encode($users);

    }


    //check to see if a user already does have a private chatroom with that user or not !

    /**
     * @param $user
     * @return bool
     */
    protected function checkPrivateMessageChatroom($user){
        $user1 = Auth::user();
        $user2 = $user;

        $chatname = $this->getPrivateChangeName($user1 , $user2);
        $chatgroup = Chatgroup::where('name',$chatname)->first();
        if (isset($chatname)){
            return $chatgroup;
        }
        return null;

    }



    protected function checkInputs($inputs){
        //TODO:check if the input is expected with $expected for every inputs and functions
        if (!$inputs->content and !gettype($inputs->content) == 'string'){
            return false;
        }
        if (!$inputs->chat_type and !gettype($inputs->chat_type) == 'string'){
            return false;
        }
        if ($inputs->chat_type != 'direct message' or $inputs->chat_type != 'chat message'){
            return false;
        }
        return true;
    }


    //////////////////////////protected variables \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    protected $expect = array('content'=>1 , '');


}