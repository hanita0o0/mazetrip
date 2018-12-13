<?php

namespace App\Http\Controllers\v1;

use App\Requesthandeling;
use App\Services\v1\EventService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Event;
use Illuminate\Support\Facades\DB;
class ApiEventController extends Controller
{
    protected $events;

    public function __construct(EventService $eventService)
    {
        $this->events = $eventService;
        $this->middleware('auth:api')->except(['activeTicket','singleEvent']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        return 1;


        $data=[];
        if ($inputs = \request()->input()) {
            $data = $this->events->showEvents($inputs);
        }else {
            $data = $this->events->showEvents();
        }
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json(0);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $respond = $this->events->CreateEvent($request);
        return    response()->json($respond);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

    }


    public function requests()
    {

        $input = \request()->input();
        if (isset($input['user_name']) and isset($input['event_name']) and isset($input['answer'])) {
            $data = $this->events->answerRequests($input);
            return response()->json($data);
        }else{
            $data = $this->events->showRequests();
            return response()->json($data);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * function for following an event
     */
    public function follow()
    {
//        return "salam";
        $inputs = \request()->input();
        if ($inputs['event_name']){
            return response()->json($this->events->follow($inputs));
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */

    public function search()
    {
       //return 'salam';
     // $data=\request()->input('key');
      //  return "salam";
       if (\request()->input('key')){

            $inputs['city'] = \request()->input('key');
            $inputs['state'] = \request()->input('key');
            $inputs['type'] = \request()->input('key');
           $inputs['club'] = \request()->input('key');
         // return $inputs;
            $data = $this->events->search($inputs);
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
     * @return string
     */
    public function destroy($id)
    {

    }

    /**
     * @return array|string
     * debug done
     */
    public function suggestionsEvents()
    {
        return $this->events->suggestionEvents();
    }


    public function isManager(){
        $data = $this->events->isManager();
        return response()->json($data);
    }

    public function singleEvent($ename){
        $api_token = null;
        if (!empty(\request()->input('api_token'))){
            $api_token = \request()->input('api_token');
        }
        $data = $this->events->showSingleEvent($ename , $api_token);
        return response()->json($data);
    }


    public function editEvent(Request $editsData,$ename){
        $data = $this->events->editEvent($ename , $editsData);
        return response()->json($data);
    }

    public function addTicket(Request $ticketData , $ename){
//        return 'salam';
        $data = $this->events->createTicket($ticketData , $ename);
        return response()->json($data);
    }

    public function singleTicket($tid){
        $data = $this->events->showSingleTicket($tid);
        return response()->json($data);
    }

    public function addTimeLine(Request $timelineData){
        return response()->json($this->events->addTimeLine($timelineData , $timelineData->type));
    }

    public function updateTicket(Request $updateData,$tid){
      //return 'hi';
        return response()->json($this->events->updateTicket($updateData , $tid));
    }

    public function showEventFollowers($ename){
        return response()->json($this->events->showEventFollowers($ename));
    }

    public function subscribe($ename){
        return response()->json($this->events->subscribe($ename));
    }
    public function deleteEvent($id){
        $data = $this->events->deleteEvent($id);
        return $data;
    }
    public function deleteTicket($id){

        $data=$this->events->deleteTicket($id);
       return $data;

    }
    public function activeTicket(){

        $activeSkip = 0;
        $activeLimit = 5;

        if (\request()->input()) {
            if (\request()->input('activeSkip')) {
                $activeSkip = \request()->input('activeSkip');
            }
            if (\request()->input('activeLimit')) {
                $activeLimit = \request()->input('activeLimit');
            }
        }
        $data=$this->events->activeTickets($activeSkip,$activeLimit);
        return $data;

    }


}
