<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Event;
use App\Eventphotos;
use App\Filter;
use App\Photo;
use App\Post;
use App\State;
use App\City;
use App\Ticket;
use App\Timeline;
use App\Type;
use App\User;
use App\Requesthandeling;
use Faker\Provider\File;
use Image;
use Illuminate\Http\Request;

class AdminEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * returning an event with all information
         *
         */
        $events = Event::all();
//        foreach ($events as $event){
//            $event->avatarImage;
//            $event->favoriteByUsers;
//            $event->tourManagers;
//            $event->tickets;
//
//
//            $timeline = $event->timeLines;
//            foreach ($timeline as $timeline){
//                $timeline->photo;
//            };
//
//            foreach($event->tickets as $ticket){
//                $tickets = $ticket->filter;
//                foreach ($tickets as $ticket){
//                    $ticket->requests;
//                }
//            }
//            $event->chat;
//            if (isset($event->chat->messages)){
//                $event->chat->messages;
//                foreach ($event->chat->messages as $message){
//                    $message->photo;
//                }
//            }
//            $event->gallery;
//            $event->posts;
//            if (isset($event->posts->likes)){
//                $event->posts->likes;
//            }
//           if ( isset($event->posts->comments)){
//               $event->posts->comments;
//               foreach ($event->posts->comments as $user){
//                   $user->owner;
//               }
//            }
//
//            $event->Filter;
//
//        }





        return view('admin.event.index', compact('events'));






    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $type = Type::Pluck('name','id')->all();
        $state = State::Pluck('name','id')->all();
        return view('admin.event.create', compact('type','state'));





    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        /**
         * this function is for creating an event
         * and attaching images to it plus
         * can add users and other options
         * but no post
         */

        $data = $request->all();
        $event = Event::create($request->all());
        if ($file = $request->file('avatar')){

            $file_name = time() . '.'. $file->getClientOriginalName();
            $path_original = storage_path('events/event_profile/original/'.$file_name);
            $path_thumbnail = storage_path('events/event_profile/thumbnail/'.$file_name);
            $path_medium = storage_path('events/event_profile/medium/'.$file_name);
            Image::make($file)->fit(150 , 150)->save($path_thumbnail);
            Image::make($file)->fit(300 , 300)->save($path_medium);
            Image::make($file)->save($path_original);

            $photo  =  Eventphotos::create(['path'=>$file_name]);
            $event->avatar = $photo->id;

        }

        //adding city to event
        $newcity = $data['city'];
        $city   = city::where('name',$newcity)->first();
        if(!$city){
            $city = City::create(['name'=>$newcity , 'state_id'=>$data['state']]);
            $event->city = $city->id;
            $city['state_id']= $request['state'];
        }else {
            $event->city = $city['id'];
        }

        Chat::create(['event_id'=>$event->id]);
        $event->save();




    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     *
     * adding manager to the event
     */

    public function addManager(Request $request, $id){

        $direct = '/admin/event/'.$id;

        $event = Event::findOrFail($id);
        $data = $request->all();
        /**
         * check if the user is exist
         */
        $user = User::where('name',$data['name'])->first();
        if($user == null){
            return redirect($direct);
        }
        /**
         * finding if the user us already in that events
         */

        foreach ($event->tourManagers as $tourManager){
            if ($data['name'] == $tourManager->name){
                return redirect($direct);
            }
        }
        $event->tourManagers()->attach($user->id , ['about'=>$data['about'] , 'role'=>$data['role']]);
        return redirect($direct);
    }

    /**
     * @param $eventid
     * @param $userid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     *
     * deleting a manager from a event
     */

    public function deleteManager($eventid , $userid){
        $event = Event::findOrFail($eventid);
        $user = User::findOrFail($userid);

        $event->tourManagers()->detach($user->id);

        return redirect('/admin/event/'.$eventid.'/managers');
    }


    /**
     * @param Request $request
     * @param $request_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     *
     *
     *
     * handle request handle the requesthandleing requests
     * comes by event or ticket
     *
     */




    public function handleRequest(Request $request,$request_id){
        $requests = Requesthandeling::findOrFail($request_id);
        $requests['status'] = $request->status;
        $requests->save();


        if ($request->request_type && $request['status'] == 2){
            $user = $requests->user;
            $ticket = $requests->filter->filterable;

            if ($user->tickets()->where('ticket_id',$ticket->id)->get()->count()){

            }else {
                $user->tickets()->attach($ticket->id);
            }
        } else if ($request->request_type && $request['status'] == 1){
//            return 'salam';
            $user = $requests->user;
            $ticket = $requests->filter->filterable;
            $user->tickets()->detach($ticket->id);
        }
        return redirect('/admin/event/'.$request->event_id);
    }












    //tickets part

    public function createTicket(Request $request,$event_id){
        $data = $request->all();
        $data['event_id'] = $event_id;
        $ticket = Ticket::create([
            'name'=>$data['name'],
            'body'=>$data['body'],
            'date'=>$data['date'],
            'event_id'=>$event_id,
            'activation_num'=>uniqid(),
            ]);
        if ($data['limit']){
            $filter =Filter::create(['limit'=>$data['limit'],'filterable_type'=>'App\Ticket','filterable_id'=>$ticket->id]);
            if ($data['request']){
                $filter->request = 1;
                $filter->save();
            }
        }else if ($data['request']){
            Filter::create(['request'=>1,'limit'=>$data['limit'],'filterable_type'=>'App\Ticket','filterable_id'=>$ticket->id]);

        }



        return redirect('/admin/event/'.$event_id);
    }


    public function deleteTicket($ticket_id , $event_id){
        Ticket::findOrFail($ticket_id)->delete();
        return redirect('/admin/event/'.$event_id);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     * creating timline and deleting them
     *
     *
     */



    public function createTimeline(Request $request){


        $data = $request->all();
        if ($file = $request->file('photo')){
            $file_name = time() . '.'. $file->getClientOriginalName();
            $path_original = storage_path('events/event_timeline/original/'.$file_name);
            $path_thumbnail = storage_path('events/event_timeline/thumbnail/'.$file_name);
            $path_medium = storage_path('events/event_timeline/medium/'.$file_name);
            Image::make($file)->fit(150 , 150)->save($path_thumbnail);
            Image::make($file)->fit(300 , 300)->save($path_medium);
            Image::make($file)->save($path_original);

            $photo  =  Photo::create(['path'=>$file_name]);
            $data['photo_id'] = $photo->id;

        }

        Timeline::create($data);
        return redirect('/admin/event/'.$request->event_id);

    }


    public function deleteTimeline($event_id , $timeline_id){

        $timeline = Timeline::findOrFail($timeline_id);

        if ($photo  =      $timeline->photo ){
//            return 'salam';
            $path_medium = '/events/event_timeline/medium/'.$timeline->photo->path;
            $path_original = '/events/event_timeline/original/'.$timeline->photo->path;
            $path_thumbnail = '/events/event_timeline/thumbnail/'.$timeline->photo->path;


            unlink(storage_path($path_original));
            unlink(storage_path($path_medium));
            unlink(storage_path($path_thumbnail));
            $photo = Photo::findOrFail($timeline->photo->id);
            $photo->delete();
        }
        $timeline->delete();
        return redirect('/admin/event/'.$event_id);

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //


        $event = Event::findOrFail($id);
        $managers  =  $event->tourManagers;

        $subscribers = $event->subscribers;

        $messages = $event->chat->messages;

        $favorites = $event->favoriteByUsers;

        $timelines = $event->timeLines;

        $posts = $event->posts;
        foreach ($event->timeLines as $timeline){
            $timeline->photo;
        };



        if (isset($event->Filter)){

                $filter = $event->Filter->requests;
        }else{
            $filter =null;
        }

        if($event->tickets){
            $tickets = $event->tickets;

        }else{
            $tickets = null;
        }
        /**
         * searching for every request base on ticket
         */


        foreach ($tickets as $ticket){
            if ($ticket->filter->count()){
                foreach ($ticket->filter as $filters){
                    $ticketrequests = $filters->requests;
                }
            }else{
                $ticketrequests = null;
            }
        }

//        return $ticketrequests;
        return view('admin.event.addUser',compact('managers' , 'event','subscribers','messages','favorites','filter','tickets','requests','ticketrequests','timelines','posts'));

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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $event = Event::findOrFail($id);

        /*
         * checking if the event does have avatar image
         */
        if ($avatar = $event->avatar->id){
            $avatar = Photo::findOrfail($avatar);
            $path_medium = '/events/event_profile/medium/'.$avatar->path;
            $path_original = '/events/event_profile/original/'.$avatar->path;
            $path_thumbnail = '/events/event_profile/thumbnail/'.$avatar->path;


            unlink(storage_path($path_original));
            unlink(storage_path($path_medium));
            unlink(storage_path($path_thumbnail));
            $avatar->delete();
        }


        if ($event->favoriteByUsers){
            $event->favoriteByUsers()->detach();
        }

        if ($event->tourManagers){
            $event->tourManagers()->detach();
        }

        if ($event->subscribers){
            $event->subscribers()->detach();
        }
        $event->chat->delete();

        foreach ($event->filter as $filter) {
            foreach ($filter->requests as $request) {
                $request->delete();
            }
            $filter->delete();
        }

        foreach ($event->tickets as $ticket){
            $ticket->delete();
        }

        foreach ($event->timeLines as $timeLine){
            $timeLine->delete();
        }

        if ($event->checkList){
            $event->checkList()->detach();
        }

        foreach ($event->post as $post){
            if ($post->photo_id){
                $photo = Photo::findOrfail($post->photo->id);
                $path_medium = '/posts/medium/'.$photo->path;
                $path_original = '/posts/original/'.$photo->path;
                $path_thumbnail = '/posts/thumbnail/'.$photo->path;
                $path_large = '/posts/large/'.$photo->path;


                unlink(storage_path($path_original));
                unlink(storage_path($path_medium));
                unlink(storage_path($path_thumbnail));
                unlink(storage_path($path_large));
                $photo->delete();
            }
            $post->delete();
        }


    }
}
