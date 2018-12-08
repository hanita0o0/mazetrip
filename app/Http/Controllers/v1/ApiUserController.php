<?php

namespace App\Http\Controllers\v1;

use App\Mail\welcomeMail;
use App\Services\v1\UserService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Image;


class ApiUserController extends Controller
{
    protected $users;

    public function __construct(UserService $service)
    {
        $this->users =$service;
        $this->middleware('auth:api')->except(['index','login',
            'createUser','searchUser','resetPasswordRequest','resetPassword','sendMail']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parameters = \request()->input();

        $emailrespond = $this->users->getExceptionsEmail($parameters['email']);
        $namerespond = $this->users->getExceptionsName($parameters['username']);
        $respond = [
            'email' => $emailrespond,
            'username'=>$namerespond,
        ];
        return response()->json($respond);

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
     * @return string
     */
    public function store(Request $request)
    {

    }


    public function login(Request $request){
        if (isset($request['password'])){
            $data = $this->users->login($request->all());

            return $data;
        }else {
            return 0;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $api_token=null;
        if (\request()->input('api_token')){
            $api_token = \request()->input('api_token');
        }
        if (empty(User::where('name',$name)->first())){
            return response()->json('no user');
        }else{
            $user = User::where('name',$name)->first();
            return response()->json($this->users->UserData($user ,10 , $api_token ));
        }


        if ($params = \request()->input()){
            return response()->json($this->users->handleInputs($params,$user));
        }else{
            if ($user) {
                $data = $this->users->UserData($user);
                return response()->json($data);
            }
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */


    public function follow($uname)
    {
        return response()->json($this->users->followUser($uname));
    }

    public function unFollow($uname){
        return response()->json($this->users->unFollow($uname));
    }


    public function showFollowedOnes($user){
        //have to complete this after completing the middleware;
        $limit = 0;
        $skip = 0;
        if (\request()->input()){
            $limit = \request()->input('limit');
            $limit = (int)$limit;
            if ($limit == 0){
                $limit = null;
            }
            $skip = \request()->input('skip');
            $skip = (int)$skip;
            if ($skip == 0){
                $skip = null;
            }
        }
//        return $limit;
        return response()->json($this->users->showUserFollowers($user , $limit , $skip));

    }
    public function showSubscribes($user){
        $data = $this->users->showClubFollower($user);
        return response()->json($data);
    }

    public function showFollowingOnes($user){
        $limit = 0;
        $skip = 0;
        if (\request()->input()){
            $limit = \request()->input('limit');
            $limit = (int)$limit;
            if ($limit == 0){
                $limit = null;
            }
            $skip = \request()->input('skip');
            $skip = (int)$skip;
            if ($skip == 0){
                $skip = null;
            }
        }
        //TODO:: show the name of the followed by user
        return response()->json($this->users->showUserFollowing($user,$limit,$skip));
    }

    public function showSelfProfile(){
        $data = $this->users->selfProfileShow();
        return response()->json($data);
    }
    //.. show edit profile
    public function showEditSelfProfile(){
        $data = $this->users->selfProfileShowEdit();
        return response()->json($data);

    }
//    //..edit profile
//    public function editSelfProfile(Request $request){
//        $user=Auth::user();
//        $user->update($request->all());
//        return response()->json($user,200);
//
//    }

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
    public function update(Request $request , $id)
    {
        $api_token=null;
        if (\request()->input('api_token')){
            $api_token = \request()->input('api_token');
        }
        $respond= array();
        if (empty($user = User::where('id',$id)->first())){
            return response()->json('no user');
        }else {
            $user = User::where('id', $id)->first();
            if ($user->api_token==$api_token) {


                if ($request->file('avatar')) {
                    $avatar = $this->users->updateAvatar($request, $user->id);
                    $respond = [
                        'avatar' => $avatar,
                    ];
                }
                if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                    $emailInputs = new Request;
                    $emailInputs->email = $request->email;
                    $emailInputs->oldpassword = $request->oldpassword;

                    $email = $this->users->UpdateEmail($emailInputs, $user);

                    $respond['email'] = $email;
                }

                if ($request->username) {
                    $user_result = $this->users->UpdateUserName($request->username, $user);
                    $respond['username'] = $user_result;
                }
                if ($request->newpassword) {
                    $pass_resul = $this->users->UpdatePassword($request->newpassword, $request->oldpassword, $user);

                    $respond['password'] = $pass_resul;
                }
                if ($request->city) {
                    $city_update = $this->users->UpdateCity($request->city, $user);

                    $respond['city'] = $city_update;
                }
                if ($request->state) {
                    $state_update = $this->users->UpdateState($request->state, $user);

                    $respond['state'] = $state_update;

                }
                if ($request->gender) {
                    $gender_update = $this->users->UpdateGender($request->gender, $user);

                    $respond['gender'] = $gender_update;

                }
                if ($request->bio) {
                    $bio_update = $this->users->UpdateBio($request->bio, $user);

                    $respond['bio'] = $bio_update;

                }
                if ($request->name_header) {
                    $name_update = $this->users->UpdateNameHeader($request->name_header, $user);

                    $respond['name_header'] = $name_update;

                }
                if ($request->phone) {
                    $phone_update = $this->users->UpdatePhone($request->phone, $user);

                    $respond['phone'] = $phone_update;

                }

                return response()->json($respond);
                //return 1;
            }
        }
        return "noaccess";

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
    }


    public function createUser(Request $request){
        $email = $this->users->getExceptionsEmail($request['email']);
        $username = $this->users->getExceptionsName($request['username']);
        if ( $email == 1 && $username == 1){
            $data = $this->users->Create($request);
            return response()->json($data);
        }else{
            return 0;
        }
    }

    public function sendMail(){
        $email = 'colonel.hossein@gmail.com';
        Mail::to($email)->send( new welcomeMail());
        return 1;
    }


    public function searchUser($uName){
        return response()->json($this->users->searchUser($uName));
    }

    public function showRequests(){
        $data = $this->users->showRequests();
        return response()->json($data);
    }

    public function answerRequests(Request $users){
        $data = $this->users->answerToRequests($users);
        return response()->json($data);
    }


    public function resetPasswordRequest(Request $request){
        if (!$request->email){
            exit();
        }
        $user = User::where('email',$request->email)->first();
        if (empty($user)){
            return 'email not found';
        }
        $this->users->resetPassword($user);
    }


    public function resetPassword(Request $request , $token){
//        $request_input = \request()->input('token');
        if (empty($token) and empty($request->password)){
            exit();
        }

        if ($token_pass = DB::table('password_resets')->where('token',$token)->first()){
            $user = User::where('email',$token_pass->email)->first();
            return $this->users->updateForgetPassword($user , $request->password);
        }
    }

}





