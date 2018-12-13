<?php

use Illuminate\Http\Request;
//use Mail;
use App\Mail\welcomeMail;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return \App\Ticket::find(1);
});

route::get('/v1/state',function (){
    return \App\State::all()->pluck('name');
});


route::post('/v1/user/login','v1\ApiUserController@login');
route::get('/v1/user/follow/{uname}','v1\ApiUserController@follow')->middleware('auth:api');
route::get('/v1/user/unfollow/{uname}','v1\ApiUserController@unFollow')->middleware('auth:api');
route::get('/v1/user/{uname}/followers','v1\ApiUserController@showFollowingOnes')->middleware('auth:api');
route::get('/v1/user/{uname}/followed','v1\ApiUserController@showFollowedOnes')->middleware('auth:api');
route::get('/v1/user/{uname}/subscribes','v1\ApiUserController@showSubscribes')->middleware('auth:api');
//.. show profile
route::get('/v1/user/self','v1\ApiUserController@showSelfProfile')->middleware('auth:api');
//..show edit profile
route::get('/v1/user/self/editshow','v1\ApiUserController@showEditSelfProfile')->middleware('auth:api');

route::get('/v1/user/requests','v1\ApiUserController@showRequests')->middleware('auth:api');
route::post('/v1/user/response','v1\ApiUserController@answerRequests')->middleware('auth:api');
route::post('/v1/user/create','v1\ApiUserController@createUser');
route::get('/v1/user/send/email','v1\ApiUserController@sendMail');
route::get('v1/user/search/{uname}' , 'v1\ApiUserController@searchUser');
route::post('v1/user/resetPassword' , 'v1\ApiUserController@resetPasswordRequest');
route::post('v1/user/resetPassword/{token}' , 'v1\ApiUserController@resetPassword');
route::resource('/v1/user',v1\ApiUserController::class);



route::post('/v1/post/create','v1\ApiPostController@createPost')->middleware('auth:api');
route::post('/v1/post/comment/{pid}','v1\ApiPostController@commentPost')->middleware('auth:api');
route::get('/v1/post/comment/delete/{cid}','v1\ApiPostController@deleteComment')->middleware('auth:api');
route::get('/v1/post/{pid}','v1\ApiPostController@showPost')->middleware('auth:api');
route::get('/v1/post/delete/{pid}','v1\ApiPostController@deletePost')->middleware('auth:api');
route::get('/v1/post/like/{pid}','v1\ApiPostController@likePost')->middleware('auth:api');
route::post('/v1/post/edit/{pid}','v1\ApiPostController@editPost')->middleware('auth:api');
route::get('/v1/post/showedit/{pid}','v1\ApiPostController@showEditPost')->middleware('auth:api');


route::get('/v1/event/follow','v1\ApiEventController@follow')->middleware('auth:api');
route::get('/v1/event/requests','v1\ApiEventController@requests')->middleware('auth:api');
route::get('/v1/event/ticket/{tid}','v1\ApiEventController@singleTicket')->middleware('auth:api');
route::put('/v1/event/ticket/{tid}','v1\ApiEventController@updateTicket')->middleware('auth:api');
//route::delete('/v1/event/ticket/{tid}','v1\ApiEventController@deleteTicket')->middleware('auth:api');
route::post('/v1/event/addticket/{ename}','v1\ApiEventController@addTicket')->middleware('auth:api');
route::post('/v1/event/addtimeline','v1\ApiEventController@addTimeLine')->middleware('auth:api');
route::get('/v1/event/suggestionevents','v1\ApiEventController@suggestionsEvents')->middleware('auth:api');
route::get('/v1/event/ismanager','v1\ApiEventController@isManager')->middleware('auth:api');
route::get('/v1/event/{ename}','v1\ApiEventController@singleEvent');
route::get('/v1/event/{ename}/followers','v1\ApiEventController@showEventFollowers')->middleware('auth:api');
route::get('/v1/event/{ename}/subscribe','v1\ApiEventController@subscribe')->middleware('auth:api');
route::post('/v1/event/edit/{ename}','v1\ApiEventController@editEvent')->middleware('auth:api');
route::get('/v1/search','v1\ApiEventController@search')->middleware('auth:api');
route::get('/v1/event/delete/{eid}','v1\ApiEventController@deleteEvent')->middleware('auth:api');
route::get('/v1/event/deleteticket/{tid}','v1\ApiEventController@deleteTicket')->middleware('auth:api');
route::get('/v1/ticketActive','v1\ApiEventController@activeTicket');


route::resource('/v1/event',v1\ApiEventController::class);


//route::get('/test', function (){
    //TODO:: complete email send
//    $beautymail = app()->make(Snowfire\Beautymail\Beautymail::class);
//    $beautymail->send('email.welcomeMail', [], function($message)
//    {
//        $message
//            ->from('hossein_tafakh@example.com')
//            ->to('colonel.hossein@gmail.com', 'yoooo')
//            ->subject('Welcome!');
//    });

//});

route::post('v1/chat/send','v1\ApiChatController@send')->middleware('auth:api');
route::post('v1/chat/createchatroom','v1\ApiChatController@createChatroom')->middleware('auth:api');
route::put('v1/chat/editchatroom/{chat_id}','v1\ApiChatController@editChatroom')->middleware('auth:api');
route::get('v1/chat/deletemessage/{mid}','v1\ApiChatController@deleteMessage')->middleware('auth:api');
route::get('v1/chat/addmembers/{user}/{cid}','v1\ApiChatController@addMembers')->middleware('auth:api');
route::get('v1/chat/kickmembers/{user}/{cid}','v1\ApiChatController@kickMembers')->middleware('auth:api');
route::resource('/v1/chat',v1\ApiChatController::class);


route::get('v1/home','v1\ApiHomeController@hello')->middleware('auth:api');
route::post('v1/suggestion','v1\ApiHomeController@suggest');
route::get('v1/types','v1\ApiHomeController@showTypes');
route::get('v1/types','v1\ApiHomeController@showTypes');
route::post('v2/home/addLocation','v2\ApiHomeController@addLocation');
route::post('v2/home/searchLocationMap','v2\ApiHomeController@searchLocationMap');
route::post('v2/home/searchLocationName','v2\ApiHomeController@searchLocationName');
route::get('v2/home/showLocation/{LId}','v2\ApiHomeController@showLocation');
route::post('v2/home/addCommentLocation/{LId}','v2\ApiHomeController@addCommentLocation');
//route::resource('/v1/chat/dmsend',v1\::class);
