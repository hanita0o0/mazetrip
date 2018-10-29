<?php

namespace App\Http\Controllers\v1;

use App\Services\v1\ChatService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiChatController extends Controller
{
    protected $chat;

    public function __construct(ChatService $chatService)
    {
        $this->chat = $chatService;
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = $this->chat->showChats();
//        $data = $this->chat->chatMembers();
        return response()->json($data);
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
        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inputs = \request()->input();
        if (array_key_exists('include',$inputs) and $inputs['include']=='members'){
            $data =$this->chat->chatMembers($id); ;
        }else {
           $data = $this->chat->showMessages($id);
        }
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return null;
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

        $data = "nothing";
        if ($request['content']){
            $data = $this->chat->sendMessage($id , $request);
        }

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return null;
    }

    public function send(Request $data)
    {
        $response = false;
        if (empty($data['content'])){
            return 'invalid inputs';
        }
        if ($data->chat_type == 'chatroom'){
            $response = $this->chat->sendMessage($data);
        }

        if ($data->user_name){
//            return 1;
            $response =  $this->chat->sendDm($data->user_name , $data);

        }

        return response()->json($response);
    }

    /**
     * @param Request $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function createChatroom(Request $data){
        $ed = $this->chat->createChatRoom($data);
        return response()->json($ed);

    }

    /**
     * @param Request $data
     * @param $chat_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function editChatroom(Request $data ,$chat_id){
        $data1 = $this->chat->editChatroom($data,$chat_id);
        return response()->json($data1);

    }

    /**
     * @param $mid
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMessage($mid){
        $data = $this->chat->deleteTheMessage($mid);
        return response()->json($data);
    }

    /**
     * @param $user
     * @param $cid
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function addMembers($user , $cid){
//        return 'salam';
        $data = $this->chat->addMember($user , $cid);
        return response()->json($data);

    }


    public function kickMembers($user , $cid){
//        return 'salam'
        $data = $this->chat->kickUserChat($user , $cid);
        return response()->json($data);

    }

}
