<?php
/**
 * Created by PhpStorm.
 * User: hossein
 * Date: 11/12/2017
 * Time: 15:34
 */

namespace App\Services\v1;



use App\Ban;
use App\Chat;
use App\City;
use App\Event;
use App\Eventphotos;
use App\Filter;
use App\Photo;
use App\Requesthandeling;
use App\State;
use App\Ticket;
use App\Ticketavatar;
use App\Timeline;
use App\TimelinePhoto;
use App\Type;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use function GuzzleHttp\Psr7\str;
use Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\comment;
use App\Gang;


class EventService
{
    //TODO: have to create a sort method to sort the events based on nearest tickets
    public function showEvents($inputs = [])
    {
        if (array_key_exists('limit', $inputs)) {
            if (array_key_exists('skip', $inputs)) {
                $events = Event::take($inputs['limit'])->skip($inputs['skip'])->where('is_active', 1)->whereDoesntHave('filter', function ($query) {
                    $query->where('hide_event', 1);
                })->latest()->get();
            } else {

                $events = Event::take($inputs['limit'])->where('is_active', 1)->whereDoesntHave('filter', function ($query) {
                    $query->where('hide_event', 1);
                })->latest()->get();
            }
        } else {
            $events = Event::take(10)->where('is_active', 1)->whereDoesntHave('filter', function ($query) {
                $query->where('hide_event', 1);
            })->latest()->get();
        }
        $data = [];
        foreach ($events as $event) {

            $data[] = [
                'name' => $event->name,
                'avatar' => $event->avatar ? $this->getImageFoldersName(null, $event->avatarImage->created_at) . $event->avatarImage->path : null,
                'about' => $event->about,
                'followers' => count($event->subscribers),
                'tour manager' => $event->tourManagers->first() ? $event->tourManagers->first()->name : null,
                //'ac_number'=>$event->activation_no,
                //'type'=>$event->types ? $event->types->name : null,


            ];
        }

        return $data;
    }

    /////////////////////////////////////create new event\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    ///
    /**
     * @param $request
     * @return string
     *
     *
     * debug is done
     */

    public function CreateEvent($request)
    {
        if (empty($request->name) or empty($request->originType) or empty($request->city) or empty($request->state) or empty($request->type)) {

            return 'wrong inputs';
        }
//        return $request;
        $user = Auth::user();
        if (Event::where('name', $request->name)->first()) {
            return 'there is event with that name';
        }
        $event = new Event();
        $last_id = Event::orderBy('created_at', 'desc')->first();
        if (empty($last_id)) {
            $last_id = 1;
        } else {
            $last_id = $last_id->id;
        }

        if ($state = State::where('name', $request->state)->first()) {
            $request['state'] = $state->id;
            $event->state = $state->id;
        } else {
            return 'state unknown';
        }
        $managers_id = array();
        if ($request->managers) {
            $managers = explode(',', $request->managers);
            foreach ($managers as $manager) {
                $manager_user = User::where('name', $manager)->first();
                if (empty($manager_user)) {
                    return 'check managers name';
                }
                $managers_id[] = $manager_user->id;
            }
        }
//        return $managers_id;
        $event->id = $last_id + 1;
        $event->name = $request->name;
        if ($request->about_team) {
            $event->about_team = $request->about_team;
        }
        if ($request->bio) {
            $event->about = $request->bio;
        }
        if ($request->header) {
            $event->header = $request->header;
        }
        if ($request->avatar) {
            $event->avatar = $this->handleAvatar($request);
        }
        //adding city to event
        $event->city = $this->getCity($request);
        // handeling the filters
        // if ($request->filters) {
        //  $event->Filter()->create($this->getFilters($request['filters']));
        // }
//        if ($request->filter){
//            $filter = array();
//            if (strpos($ticketData->filter,'male') !== false){
//                $filter['gender'] = 1;
//            }
//            if (strpos($ticketData->filter, 'female')!== false){
//                $filter['gender'] = 0;
//            }
//            if (strpos($ticketData->filter , 'request')!== false){
//                $filter['request'] = 1;
//            }

        if (isset($managers_id)) {

            array_push($managers_id, $user->id);
            $managers_id = array_unique($managers_id);
            $key = array_search($user->id, $managers_id);
            unset($managers_id[$key]);
        }
        //handleing the subtypes
        $types = $this->getType($request['type']);
        foreach ($types as $type) {
            $event->gangs()->attach($type);
        }
        //handeling the origintypes
        if ($request['originType']) {

            $originTypes = explode(',', $request['originType']);
            foreach ($originTypes as $type) {
                $event->types()->attach($type);
            }
        }


        //handeling the toureManagers part

        $event->tourManagers()->attach($user->id);
        $event->tourManagers()->attach($managers_id);
        $event->subscribers()->attach($user->id);
        $event->subscribers()->attach($managers_id);

        Chat::create([
            'chatable_type' => 'App\Event',
            'chatable_id' => $event->id,
        ])->save();
        $event->save();
        return 'event created';
    }


////////////////////////////////////follow\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param $input
     * @return string
     *
     * debug is done
     */
    public function follow($input)
    {
        $user = Auth::user();
        if ($input['event_name']) {
            if ($event = Event::where('name', $input['event_name'])->first()) {
                if ($event->subscribers->contains($user->id)) {
                    return 'user is already following this event';
                } else {
                    if (isset($event->Filter)) {
                        if ($genderFilter = $event->Filter->gender) {
                            if ($gender = $user->gender) {
                                if ($gender != $genderFilter) {
                                    return 'cant follow this event';
                                }
                            } else {
                                return 'we need to know your gender';
                            }
                        }
                        if ($event->Filter->request) {
                            $filter = $event->Filter;
                            $userRequest = $user->requests->where('filter_id', $filter->id)->first();
                            if (empty($userRequest)) {
                                $user->requests()->create(['filter_id' => $event->Filter->id]);
                                return 'request sended';
                            }
                            if ($userRequest->status == 0) {
                                return 'need approval';
                            }
                            if ($userRequest->status == 1) {
                                return 'you have been rejected';
                            }
                        }
                    }
                    $event->subscribers()->attach($user->id);
                    return 'following now';
                }
            } else {
                return 'no event with this name';
            }
        }
        return 'invalid inputs';

    }

/////////////////////////////////////answer requests \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


    /**
     * @param $input
     * @return string
     *
     * debug is done
     */
    public function answerRequests($input)
    {

        $user = Auth::user();
        if ($user_requested = User::where('name', $input['user_name'])->first()) {
            if ($event = Event::where('name', $input['event_name'])->first()) {
                if ($event_admins = $event->tourManagers->where('name', $user->name)) {
                    if ($input['answer'] == 'approve') {
                        $filter_id = $event->Filter->id;
                        if ($event->subscribers->contains($user_requested->id)) {
                            return 'user alredy in this event';
                        }
//                        $user_requested->requests->where('filter_id',$filter_id);
                        $requesthandeling = Requesthandeling::where([['user_id', $user_requested->id], ['filter_id', $filter_id]])->first();
                        if (empty($requesthandeling)) {
                            return 'no request exist';
                        }
                        $event->subscribers()->attach($user_requested->id);
                        $requesthandeling['status'] = 2;
                        $requesthandeling->save();
                        return 'user added to ' . $event->name . ' event';
                    } else if ($input['answer'] == 'reject') {
                        $filter_id = $event->Filter->id;
                        $requesthandeling = Requesthandeling::where([['user_id', $user_requested->id], ['filter_id', $filter_id]])->first();
                        if (empty($requesthandeling)) {
                            return 'no request exist';
                        }
                        $requesthandeling->status = 1;
                        $requesthandeling->save();
                        return 'user got rejected from ' . $event->name;

                    }
                } else {
                    return 'you are not manager';
                }

            } else {
                return 'event not exist';
            }
        } else {
            return 'couldent find the user';
        }

        return "check the inputs";
    }


    /**
     * @return array
     *
     * debug is done
     */
    public function showRequests()
    {
        $user = Auth::user();
        if (count($user->toureManagers->toArray()) >= 1) {
            $data = [];
            foreach ($user->touremanagers as $event) {
                if ($filter = $event->Filter) {

                    foreach ($filter->requests->where('status', 0)->all() as $requests) {
//                       $entry = array();
                        $user = User::find($requests->user_id);
                        $data[] = [
                            'user_name' => $user->name,
                            'date' => $requests->created_at,
                            'event' => $event->name,
                            'gender' => $user->gender,
                            'status' => 'need approval'
                        ];
//                       $data['needapproval'] = $entry;
                    }
                    foreach ($filter->requests->where('status', 1) as $requests) {
                        $user = User::find($requests->user_id);
                        $data[] = [
                            'user_name' => $user->name,
                            'date' => $requests->created_at,
                            'event' => $event->name,
                            'gender' => $user->gender,
                            'status' => 'rejected'
                        ];
                    }
                }
            }
            return $data;
        }
    }

/////////////////////////////////////show chat messenger\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//
//    public function showChat($input){
//        $user = Auth::user();
//        $event = Event::where('name',$input['event_name'])->first();
//        if (!$user->subscribers->contains($event->id)){
//            return 'access denied';
//        }
//        if ($event->is_active == 0 ){
//            return 'event deleted';
//        }
//
//        $chat = $event->Chat;
//        $limits = [
//            'limit'=> $input->limit ? $input->limit : 10 ,
//            'take'=>$input->take ? $input->take : 0,
//            ];
//        $messages = $this->messageStructure($chat,$limits);
//
//        return $messages;
//
//    }

    /**
     * @param int $skip
     * @param int $take
     * @return array|string
     *
     * debug done
     */
    public function suggestionEvents()
    {

        $user = Auth::user();
        if ($user->city) {
            $city = $user->city->id;
        } else {
            return 'please add city to your profile';
        }
        $event_city = Event::where(['city' => $city, 'is_active' => true])->get();
        $eventSortedCity = array();
        if (isset($event_city)) {
            foreach ($event_city as $event) {
                $ticketSortedCity = $this->getTicketOfEvent($event);
                $eventSortedCity[] = $ticketSortedCity ? $ticketSortedCity->event : null;
            }
        }
        if (count($eventSortedCity) <= 10) {
            if ($state = $user->state) {
                $events2 = Event::where(['state' => $state->id, 'is_active' => true])->get();
                foreach ($events2 as $event) {
                    $ticketSortedState = $this->getTicketOfEvent($event);
                    $eventSortedState[] = $ticketSortedState ? $ticketSortedState->event : null;
                }
                $data = array_unique(array_merge($eventSortedCity, $eventSortedState), SORT_REGULAR);
                $data = array_filter($data);
            }
            return $this->eventStructure($data);
        }
        return $this->eventStructure($eventSortedCity);
    }

////////////////////////////////////show single event\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\//

    /**
     * @param $ename
     * @param int $subsn
     * @return array|null|string
     *
     *
     * debug is done
     */
    public function showSingleEvent($ename, $api_token = null)
    {

        $event = Event::where('name', $ename)->first();


        if (empty($event)) {
            return "no event";
        }
        if (!$event->is_active) {
            return null;
        }

        if (isset($api_token)) {
            $requested_user = User::where('api_token', $api_token)->first();
        } else {
            $requested_user = null;
        }
        $event_managers = $this->eventManagers($event);
        $timeLineData = null;
        if ($event->timeLines->first()) {
            foreach ($event->timeLines as $timeLine) {
                $photo = $timeLine->photo_id ? $timeLine->photo->path : null;
                $timeLineData[] = [
                    'name' => $timeLine->name,
                    'text' => $timeLine->text,
                    'time' => $timeLine->time,
                    'photo' => $photo,
                ];
            }
        }
//        if ($timeLineData){
//            $timeLineData = null;
//        }
        $origin=[];
        $originType=[];
        foreach($event->types as $type){
            $origin[] =$type->name;
            $originType[]=['id'=>$type->id,'name'=>$type->name];

        }
        $originShow=implode("،",$origin);



        $tagss = [];
        foreach ($event->gangs as $tag) {
            $tagss[] = $tag->name;
        }
        $tags = implode("،", $tagss);
        $eventData = [
            'id'=>$event->id,
            'requested_user' => $requested_user ? $requested_user->name : null,
            'club_name' => $event->name,
            'header' => $event->header ? $event->header : null,
            'bio' => $event->about ? $event->about : null,
            'about_team' => $event->about_team ? $event->about_team : null,
            'managers' => $event_managers,
            'originTypeShow'=>$originShow,
            'originType'=>$originType,
            'tags' => $tags,
            'avatar' => $event->avatar ? $this->getImageFoldersName(null, $event->avatarImage->created_at) . $event->avatarImage->path : null,
            'state' => $event->state ? $event->State->name : null,
            'city' => $event->city ? $event->City->name : null,
            'tickets' => $event->tickets ? $this->ticketStruct($event->tickets) : null,
            'timeline' => $timeLineData,
//            'checklist'=>$event->checkList ? $event->checkList : null,
            'posts' => $event->posts ? $this->getPostOfEvent($event->posts) : null,
            'post_numbers' => $event->posts ? count($event->posts) : 0,
            'ticket_numbers' => $event->tickets ? count($event->tickets) : 0,
            'followers' => $event->subscribers ? count($event->subscribers) : 0,
            'is_subscriber' => $event->subscribers->contains($requested_user->id) ? true : false,
        ];
        return $eventData;
    }

    public function editEvent($eventName, $editData)
    {
        $event = Event::where('name', $eventName)->first();
        if (empty($event)) {
            return "no club exist";
        }
        $user = Auth::user();
        /**
         * name
         * about
         * about team
         * add tourmanager
         * kick tour manager
         * header
         * kick users
         * delete ticket
         * add filter
         * remove filter
         *
         */

        if (!($event->tourManagers->first()->id == $user->id)) {
            return 'no access';
        }
        if (empty($editData->name) or empty($editData->originType) or empty($editData->city) or empty($editData->state) or empty($editData->tags)) {

            return 'fill required field';
        }
        if ($editData->name) {
            if (Event::where('name', $editData->name)->first()) {
                return 'name club taken before';
            }
            $event->name = $editData->name;
        }
        if ($editData->header) {
            $event->header = $editData->header;
        }
        if ($editData->about) {
            $event->about = $editData->about;
        }
        if ($editData->about_team) {
            $event->about_team = $editData;
        }
        if ($editData->state) {
            $event->state = $this->getState($editData->state);
        }
        if ($editData->city) {
            $event->city = $this->getCity($editData);
        }

        if ($editData->originType) {
            $input['originType'] = $editData->originType;

            $originTypes = explode(',', $input['originType']);

            $event->types()->sync($originTypes);
        }

        if ($editData->hasFile('avatar')) {
            //todo:have to delete the last avatar

            $event->avatar = $this->updateAvatar($editData, $event);
        }
        if ($editData->tags) {
            $request['tags'] = $editData->tags;
           // return  $request['tags'];
            $types = $this->getType($request['tags']);

                $event->gangs()->sync($types);

        }
        $managers_id = array();
        if ($editData->addManager) {
            $managers = explode(',', $editData->addManager);
            foreach ($managers as $manager) {
                $manager_user = User::where('name', $manager)->first();
                if (empty($manager_user)) {
                    return 'no user';
                }
                $managers_id[] = $manager_user->id;
            }

            if (isset($managers_id)) {

                array_push($managers_id, $user->id);
                $managers_id = array_unique($managers_id);

                foreach ($managers_id as $item) {
                    if (!($event->tourManagers->contains($item))) {
                        $event->tourManagers()->attach($item);

                    }
                }

                foreach ($managers_id as $item) {
                    if (!($event->subscribers->contains($item))) {

                        $event->subscribers()->attach($item);
                    }
                }
            }
        }


//                if ($about = $event->tourManagers()->where('user_id', $managerUser->id)->first()->pivot->about) {
//                } else {
//                    $about = null;
//                }
//
//                $event->tourManagers()->updateExistingPivot($managerUser->id, ['role' => $editData->managerRole, 'about' => $about]);
//                if ($editData->aboutManager) {
//                    $event->tourManagers()->updateExistingPivot($managerUser->id, ['about' => $editData->aboutManager]);
//                }
        $kickManagers_id = array();
        if ($editData->kickManager) {
            if ($event->tourManagers()->first()->pivot->user_id == $user->id) {
                $kickManagers = explode(',', $editData->kickManager);
                foreach ($kickManagers as $kickManager) {

                    $kickManager_user = User::where('name', $kickManager)->first();
                    if (empty($kickManager_user)) {
                        return 'no user';
                    }
                    if ($kickManager_user->id == $user->id) {
                        return ' you can not kick yourself';
                    }
                    $kickManagers_id[] = $kickManager_user->id;
                }
                if (isset($kickManagers_id)) {

                    foreach ($kickManagers_id as $item) {
                        if ($event->tourManagers->contains($item)) {
                            $event->tourManagers()->detach($item);


                        }
                    }


                    }

                }
            }

//            if ($editData->kickUser) {
//                $kickUser = User::where('name', $editData->kickUser)->first();
//                if (empty($kickUser)) {
//                    return 'no user';
//                }
//                if (!$event->subscribers->contains($kickUser->id)) {
//                    return 'user is not subscriber';
//                }
//                $event->subscribers()->detach($kickUser->id);
//                Ban::create(['banable_type' => 'App\Event', 'banable_id' => $kickUser->id]);
//            }
        // adding the filter
//        if ($editData->genderFilter) {
//
//            if (empty($filter)) {
//                $filter = $this->getEventFilter($event);
//            }
//            if ($editData->genderFilter == "male") {
//                $filter->gender = 1;
//            } else if ($editData->genderFilter == "female") {
//                $filter->gender = 0;
//            } else {
//                return 'wrong input';
//            }
//        }
//            if ($editData->requestFilter) {
//                if (empty($filter)) {
//                    $filter = $this->getEventFilter($event);
//                }
//                if ($editData->requestFilter == "1") {
//                    $filter->request = 1;
//                } else if ($editData->requestFilter == "0") {
//                    $filter->request = 0;
//                } else {
//                    return 'wrong input';
//                }
//            }
//            if ($editData->hideEvent) {
//                if (empty($filter)) {
//                    $filter = $this->getEventFilter($event);
//                }
//                if ($editData->hideEvent == "1") {
//                    $filter->hide_event = 1;
//                } else if ($editData->hideEvent == "0") {
//                    $filter->hide_event = 0;
//                } else {
//                    return "wrong input";
//                }
//
//            }
        //saving  event and and filter
//            if (isset($filter)) {
//                $filter->save();
//            }
       if( $event->save()){
           return true;
       }



        /**
         * @param $ticketData
         * @param $ename
         * @return bool|string
         *
         * debug done;
         */
    }

    public function createTicket($ticketData, $ename)
    {
//        return $ticketData->date;
        $event = Event::where('name', $ename)->first();
        if (empty($event)) {
            return 'no event';
        }
        if (!$ticketData->name or !$ticketData->body or !$ticketData->date or !$ticketData->city or !$ticketData->state or !$ticketData->address or !$ticketData->managers or !$ticketData->end_date) {
            return 'not enought inputs';
        }
        if ($event->tourManagers->first()->id != Auth::user()->id) {
            return 'no permission';
        }
        $state = $this->getState($ticketData->state);
        if (empty($state)) {
            return 'state unknown';
        }
        $price = 0;
        if ($ticketData->price) {
            $price = (int)$ticketData->price;
        }
        $ticket = [
            'name' => $ticketData->name,
            'body' => $ticketData->body,
            'date' => $ticketData->date,
            'end_date' => $ticketData->end_date,
            'event_id' => $event->id,
            'price' => $price,
            'state' => $state,

            'city' => $this->getCity($ticketData),
            'address' => $ticketData->address,
            'is_active' => 1,
            'activation_num' => uniqid(),
            'avatar_id' => $this->handleTicketAvatar($ticketData),
        ];

        if ($ticketData->filter) {
            $filter = array();
            if (strpos($ticketData->filter, 'male') !== false) {
                $filter['gender'] = 1;
            }
            if (strpos($ticketData->filter, 'female') !== false) {
                $filter['gender'] = 0;
            }
            if (strpos($ticketData->filter, 'request') !== false) {
                $filter['request'] = 1;
            }

            if ($ticketData->limit) {
                $number = intval($ticketData->limit);
                if (is_int($number)) {
                    $filter['limit'] = $ticketData->limit;
                }
            }
        }
        $managers = explode(',', $ticketData->managers);
        $role = explode(',', $ticketData->manager_role);
        $ticketManagers = null;
        $n = 0;
//        return $ticket;
        $ticket = Ticket::create($ticket);
        foreach ($managers as $manager) {
            $user = User::where('name', $manager)->first();
            if (empty($user)) {
                continue;
            }
            if ($event->tourManagers->contains($user->id)) {
                $ticketManagers [] = $user->id . $role[$n];
                $ticket->managers()->attach($user->id, ['role' => $role[$n]]);
            }
            $n++;
        }
        $filter['filterable_id'] = $ticket->id;
        $filter['filterable_type'] = 'App\Ticket';
        Filter::create($filter);
        return $ticket->id;
    }


    public function updateTicket($ticketData, $tid)
    {
        $response_data = false;
        $ticket = Ticket::find($tid);
        if(empty($ticket)){
            return "no ticket";
        }
        $event = $ticket->event;
        $newManager = null;
        $deletedManager = null;
        $deleteTimeLine = null;
        $update = null;
        if ($event->tourManagers->first()->id != Auth::user()->id) {
            return 'no permission';
        }
//        if ($ticketData->addManagers){
//            $managers = explode(',' , $ticketData->addManagers);
//            foreach ($managers as $manager){
//                $user = User::where('name',$manager)->first();
//                if (empty($user)){
//                    continue;
//                }
//                if ($event->tourManagers->contains($user->id)) {
//                    $newManager[] = $user->id;
//                }
//            }
//        }
        if ($ticketData->deleteManagers) {
            $managers = explode(',', $ticketData->deleteManagers);
            foreach ($managers as $manager) {
                $user = User::where('name', $manager)->first();
                if (empty($user)) {
                    continue;
                }
                $deletedManager[] = $user->id;
            }
        }
        if ($ticketData->deleteTimeLine) {
//            return $ticketData->deleteTimeLine;
            $idies = explode(',', $ticketData->deleteTimeLine);
            $idies = array_map('intval', $idies);
            $timelines = $ticket->timeLine;
            $timelines = $timelines->whereIn('id', $idies);
            $images = $timelines->pluck('photo_id');
            // return $images;
            $images = $images->toArray();
            //return $images;
            $images = array_diff($images, [null]);
            if (empty($images) !== true) {
                $timeline_photos = DB::Table('timeline_photos')->whereIn('id', $images)->get();
                // return $timeline_photos;
                foreach ($timeline_photos as $timeline_photo) {
                    $file = $timeline_photo->path;
                    $path_original = storage_path('photos/timeline/original' . $this->getImageFoldersName(null, $timeline_photo->created_at) . $file);
                    // return $path_original;
                    $path_thumbnail = storage_path('photos/timeline/thumbnail' . $this->getImageFoldersName(null, $timeline_photo->created_at) . $file);
                    $path_medium = storage_path('photos/timeline/medium' . $this->getImageFoldersName(null, $timeline_photo->created_at) . $file);
                    unlink($path_original);
                    unlink($path_thumbnail);
                    unlink($path_medium);

                    DB::Table('timeline_photos')->where('id', $timeline_photo->id)->delete();
                }
            }
            foreach ($timelines as $timeline) {
                $timeline->delete();
            }
            $response_data['timeLine'] = "done";
        }

        if ($ticketData->name) {
            $update['name'] = $ticketData->name;
            $response_data['name'] = "done";
        }
        if ($ticketData->address) {
            $update['address'] = $ticketData->address;
            $response_data['address'] = "done";
        }
        if ($ticketData->body) {
            $update['body'] = $ticketData->body;
            $response_data['body'] = "done";
        }
        if ($ticketData->city) {
            $update['city'] = $this->getCity($ticketData);
            $response_data['city'] = "done";
        }
        if ($ticketData->state) {
            $state = $this->getState($ticketData->state);
            if (empty($state)) {
                return 'state unknown';
            } else {
                $update['state'] = $state;
                $response_data['state'] = "done";
            }
        }
        if ($ticketData->date) {
            $update['date'] = $ticketData->date;
            $response_data['date'] = "done";
        }
        if ($ticketData->end_date) {
            $update['end_date'] = $ticketData->end_date;
            $response_data['end_date'] = "done";
        }
        if ($ticketData->avatar) {
            $update['avatar_id'] = $this->updateTicketAvatar($ticketData, $ticket);
            $response_data['avatar'] = "done";
        }


        if ($ticketData->price) {
            $price = (int)$ticketData->price;
            if (is_int($price) == true and $price > 0) {
                $update['price'] = $price;
            } else {
                $response_data['price'] = 'data is incorrect';
            }
            $response_data['price'] = "done";
//            $update['price'] = $ticketData->price;
        }


        if ($ticketData->addManagers and $ticketData->managerRoles) {
            $role = array();
            $managers = explode(',', $ticketData->addManagers);
//           return $managers;
            $role = explode(',', $ticketData->managerRoles);
            // return $role;
            $ticketManagers = null;
            $n = 0;
            foreach ($managers as $manager) {
                $user = User::where('name', $manager)->first();
                if (empty($user)) {
                    continue;
                }
                if ($event->tourManagers->contains($user->id)) {
                    //$ticketManagers []= $user->id . $role[$n];
                    if (!($ticket->managers->contains($user->id))) {
                        $ticket->managers()->attach($user->id, ['role' => $role[$n]]);
                        $response_data['addmanager'.$n] = "done";
                    }
                }

                $n++;
            }
//            foreach ($managers as $manager) {
//                $user = User::where('name', $manager)->first();
//                if (empty($user)) {
//                    $response_data['add_managers'] = "some managers name was not found";
//                    continue;
//                }
//                if (!$ticket->managers->contains($user->id)) {
//                    $ticket->managers()->attach($user->id, ['role' => $role[$n]]);
//                }
//                $n++;
//            }

        }
        if ($deletedManager) {
            $ticket->managers()->detach($deletedManager);
            $response_data['delete_managers'] = "done";
        }

        if ($update != null) {
            $ticket->update($update);
        }
        return $response_data;

    }

    /**
     * @param $timeLineData
     * @param $type
     * @return bool|string
     *
     * debug done
     * add timeline to events and tickets both
     */
    public function addTimeLine($timeLineData, $type)
    {

        if (empty($timeLineData->name) or empty($timeLineData->text) or empty($timeLineData->time) or empty($type)) {
            return 'not enought inputs';
        }
        if ($type == 'ticket') {
            if (empty($timeLineData->ticketid)) {
                return 'not enought inputs';
            }
            $ticket = Ticket::find($timeLineData->ticketid);
            if (empty($ticket)) {
                return 'no ticket';
            }

            $event = $ticket->event;
            if (!$event->tourManagers->contains(Auth::user()->id)) {
                return 'no access';
            }
            $timeline = [
                'name' => $timeLineData->name,
                'text' => $timeLineData->text,
                'time' => $timeLineData->time,
                'timelineable_id' => $timeLineData->ticketid,
                'timelineable_type' => "App\Ticket",
            ];
            if ($timeLineData->hasFile('photo')) {
                $timeline['photo_id'] = $this->handleTimeLineImage($timeLineData);
            }
//            return $timeline;
            $timeline = Timeline::create($timeline);
            $timeline->timelineable_type = 'App\Ticket';
            $timeline->timelineable_id = $timeLineData->ticketid;
            $timeline->update();
            return true;
        }
        if ($type == 'club') {
            $timeline = array();
            $event = Event::where('name', $timeLineData->club)->first();
            if (empty($event)) {
                return 'no club';
            }
            $timeline = [
                'name' => $timeLineData->name,
                'text' => $timeLineData->text,
                'time' => $timeLineData->time,
                'timelineable_id' => $event->id,
                'timelineable_type' => 'App\Event',
            ];
            if ($timeLineData->hasFile('photo')) {
                $timeline['photo_id'] = $this->handleTimeLineImage($timeLineData);
            }
            $timeline = Timeline::create($timeline);
            $timeline->timelineable_type = 'App\Event';
            $timeline->timelineable_id = $event->id;
            $timeline->update();
            return true;
        }
    }


    //TODO::create to show who is banned function
    public function showBanned($ename)
    {
        $event = Event::where('name', $ename)->first();
        if (empty($event)) {
            return 'event unknown';
        }
        if ($event->tourManagers->contains(Auth::user()->id)) {
            $banData = null;
            foreach ($event->ban as $ban) {
                $banData = [
                    'user' => $ban->banable, //TODO::complete this part and debug
                    'user_avatar' => $ban->banable->avatar_id ? $ban->banable->avatar->path : null,
                    'event' => $event->name,
                ];
            }
        }

    }

////////////////////////////////////search\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\//
    public function search($input)
    {
        $data = new \Illuminate\Database\Eloquent\Collection;
        $cities = new \Illuminate\Database\Eloquent\Collection;
        $states = new \Illuminate\Database\Eloquent\Collection;
        $types = new \Illuminate\Database\Eloquent\Collection;
        $clubs = new \Illuminate\Database\Eloquent\Collection;
        if (isset($input['city'])) {
            $cities = $this->searchCity($input);
        }
        if (isset($input['state'])) {
            $states = $this->searchState($input);
        }
        if (isset($input['type'])) {

            $types = $this->searchType($input);
        }
        if (isset($input['club'])) {

            $clubs = $this->searchClub($input);
        }

        //TODO::have to add TAGS

        $events = $data->merge($states)->merge($cities)->merge($types)->merge($clubs);

        //$events = $data->merge($cities);
        //return gettype($events);
        return $this->eventStructure($events);
        //return dd($events);


    }


    /**
     * @return bool
     * debug done
     */
    public function isManager()
    {
        $user = Auth::user();
        if ($user->touremanagers->first()) {
            return true;
        }
        return false;
    }

    public function showSingleTicket($tid)
    {
        $ticket = Ticket::find($tid);
        if (empty($ticket)) {
            return ' no ticket';
        }
        $event = $ticket->event;
        $user = Auth::user();
        if ($ticket->managers->contains($user->id) or $event->tourManagers->contains($user->id)) {
            return $this->singleTicketData($ticket);
        }

//        if ($ticket->filter){
//            $filter = $ticket->filter;
//            if (empty($user->gender)){
//                return 'please update gender setting';
//            }
//            if ($filter->gender != $user->gender) {
//                return 'no access';
//            }
//        }
        if ($event->subscribers->contains($user->id)) {

            return $this->singleTicketData($ticket);
        }

        if ($event->Filter and ($event->Filter->request == true)) {
            return 'subscribe first';
        }
        if (!$event->Filter and !$event->Filter->request and !$event->Filter->hide_event) {
            return $this->singleTicketData($ticket);
        }
        return null;

    }

    public function subscribe($ename)
    {
        $event = Event::where('name', $ename)->first();
        if (empty($event)) {
            return 'no event with this name';
        }
        if ($event->subscribers->contains(Auth::user()->id)) {
            if ($event->tourManagers->contains(Auth::user()->id)) {
                return 'you are manager';
            }
            $event->subscribers()->detach(Auth::user()->id);
            return 'unsubscribed';
        }
        if ($filter = $event->filter) {
            if ($filter->hide_event) {
                return 'no access';
            }
            if ($filter->request) {
                Requesthandeling::create(['user_id' => Auth::user()->id, 'filter_id' => $filter->id, 'status' => 0]);
                return 'request sended';
            }
        }
        $event->subscribers()->attach(Auth::user()->id);
        return 'subscribed';
    }

    public function showEventFollowers($ename)
    {
        $event = Event::where('name', $ename)->first();
        $user = Auth::user();
        $output = null;
        if (empty($event)) {
            return 'no event';
        }
        if ($event->subscribers->contains($user)) {
            $users = $event->subscribers;
            $avatar_idies = $users->pluck('avatar_id');
            $photos = Photo::find($avatar_idies);

            foreach ($users as $user) {
                $output[] = [
                    'name' => $user->name,
                    'avatar' => $user->avatar_id ? $photos->where('id', $user->avatar_id)->first()->path : null,
                ];
            }
        }

        return $output;

    }


////////////////////////////////////mini methods\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\//
///

    protected function singleTicketData($ticket)
    {
        $timelineData = array();
        if (isset($ticket->timeLine)) {
            $timelines = $ticket->timeLine;
            foreach ($timelines as $timeline) {
                $timelineData[] = [
                    'id' => $timeline->id,
                    'name' => $timeline->name ? $timeline->name : null,
                    'text' => $timeline->text ? $timeline->text : null,
                    'time' => $timeline->time ? $timeline->time : null,
                    'photo' => $timeline->photo_id ? $timeline->photo->path : null,
                    'created_at' => $timeline->created_at ? $timeline->created_at : null,

                ];
            }
        }
        $managers = null;
        $ticket_managers = $ticket->managers;
//        return $ticket_managers[0]->pivot;
        foreach ($ticket_managers as $ticket_manager) {
            $managers[] = [
                'name' => $ticket_manager->name,
                'avatar' => $ticket_manager->avatar_id ? $ticket_manager->avatar->path : null,
                'role' => $ticket_manager->pivot->role,
            ];
        }

        $startDates= explode(' ',$ticket->date);
        $startdate=explode('-',$startDates[0]);
        $startdata=[
            'year'=>$startdate[0],
            'month'=>$startdate[1],
            'day'=>$startdate[2]
        ];
        $starttimes=explode(':',$startDates[1]);
        $starttime=[
            'hour'=>$starttimes[0],
            'minute'=>$starttimes[1],
            'second'=>$starttimes[2]
        ];
        $endDates= explode(' ',$ticket->end_date);
        $enddate=explode('-',$endDates[0]);
        $enddata=[
            'year'=>$enddate[0],
            'month'=>$enddate[1],
            'day'=>$enddate[2]
        ];
        $endtimes=explode(':',$endDates[1]);
        $endtime=[
            'hour'=>$endtimes[0],
            'minute'=>$endtimes[1],
            'second'=>$endtimes[2]
        ];

        $data = [
            'id' => $ticket->id,
            'name' => $ticket->name,
            'body' => $ticket->body,
            'avatar' => $ticket->avatar_id ? $this->getImageFoldersName(null, $ticket->avatar->created_at) . $ticket->avatar->path : null,
            'fullStartDate'=>$ticket->date,
            'fullEndDate'=>$ticket->end_date,
            'startDate' =>$startdata,
            'startTime'=>$starttime,
            'endDate'=>$enddata,
            'endTime'=>$endtime,
            'active' => $ticket->is_active,
            'club' => $ticket->event->name,
            'price' => $ticket->price,
            'limit' => $ticket->filter ? $ticket->filter->limit : null,
            'city' => $ticket->city ? $ticket->City->name : null,
            'state' => $ticket->state ? $ticket->State->name : null,
            'created_at' => $ticket->created_at,
            'managers' => $managers,
            'timeLine' => $timelineData,
            'members_number' => $ticket->user ? count($ticket->user) : null,
        ];
        $authUser = Auth::user();
        if ($ticket->user->contains($authUser->id) or $ticket->event->tourManagers->contains($authUser->id)) {
            $data['members'] = $ticket->user ? $ticket->user->pluck('name') : null;
            $data['address'] = $ticket->address;
        }
        return $data;
    }


    protected function getState($state)
    {
        $state = State::where('name', $state)->first();
        if (empty($state)) {
            return null;
        }
        return $state->id;
    }

    protected function getEventFilter($event)
    {
        if (empty($event->Filter)) {
            $filter = Filter::create(['filterable_type' => 'App\Event', 'filterable_id' => $event->id]);
            return $filter;
        }
        return $event->Filter;
    }

    protected function addManager($event, $user)
    {
        $event->tourManagers()->attach($user->id);
    }

    protected function getPostOfEvent($posts)
    {
        foreach ($posts as $post) {
            $data[] = [
                'pid' => $post->id,
//                'writer'=>$post->user->name,
//                'writer_avatar'=>$post->user->avatar_id ? $post->user->avatar->path : null,
                'image' => $post->media_id ? $this->getImageFoldersName(null, $post->photo->created_at) . $post->photo->name : null,
                'content' => $post->body,
//                'cm'=>isset($post->comments[0]) ? $this->getPostComments($post): 0,
                'cm_numbers' => count($post->comments),
                'likes' => isset($post->likes[0]) ? count($post->likes) : 0,
//                'like_users'=>isset($post->likes[0]) ? $this->getPostLike($post) : 0,

            ];
        };
        if (isset($data)) {
            return $data;
        }
        return null;
    }


    protected function getPostComments($post)
    {
        $comments = Comment::where('post_id', $post->id)->latest()->get()->take(2);
        foreach ($comments as $comment) {
            $data[] = [
                'writer' => $comment->user->name,
                'writer_avatar' => $comment->user->avatar_id ? $comment->user->avatar->path : null,
                'text' => $comment->body,
                'time' => $comment->created_at,

            ];
        }
        if (!empty($data)) {
            return $data;
        }
        return null;
    }

    protected function getPostLike($post)
    {
        $likes = $post->likes->take(3);
        foreach ($likes as $like) {
            $data[] = [
                'user_name' => $like->name,
                'user_avatar' => $like->avatar_id ? $like->avatar->path : null
            ];
        }
        if (!empty($data)) {
            return $data;
        }
        return null;
    }


    protected function getTicketOfEvent($event)
    {
        if (isset($event->tickets)) {
            $mytime = Carbon::now();
            $ticket = $event->tickets()->where('date', '>', $mytime)->orderBy('date', 'desc')->first();
            return $ticket;
        }
        return null;
    }


    protected function messageStructure($chat, $limit = ['take' => 20, 'limit' => 0])
    {
        $messages = $chat->messages->take($limit['take'])->latest()->get();

        $data = [];
        foreach ($messages as $message) {
            $writer = $message->writer;
            $data [] = [
                'id' => $message->id,
                'user_name' => $writer->name,
                'user_avatar' => $message->writer->avatar_id ? $writer->avatar->path : null,
                'replay_to_id' => $message->parent_id ? $message->parent_id : null,
                'date' => $message->created_at,
                'content' => $message->content ? $message->content : null,
                'image' => $message->messagephoto_id ? $message->photo->path : null,

            ];
        }
    }


    protected function eventStructure($events)
    {
        $data = [];
        foreach ($events as $event) {

//            return $event;
//            if ($user = Auth::user() and $user->events->contains($event->id)){
//
//            }
            if (isset($event->Filter) and isset($event->Filter->hide_event)) {
                continue;
            }
            if ($event->is_active == 0) {
                continue;
            }
            $data[] = [
                'name' => $event->name,
                'avatar' => $event->avatar ? $this->getImageFoldersName(null, $event->avatarImage->created_at) . $event->avatarImage->path : null,
                'about' => $event->about,
                'followers' => count($event->subscribers),
                'tour manager' => $event->tourManagers->first() ? $event->tourManagers->first()->name : null,
//                'ac_number'=>$event->activation_no,
                // 'type'=>$event->types ? $event->types->name : null,


            ];
        }
        return $data;
    }

    protected function searchType($input)
    {
        $data = new \Illuminate\Database\Eloquent\Collection;
//        if (isset($input['type']) and  isset($input['city'])){
//            if ($type = Type::where('name',$input['type'])->first() and $city = City::where('name',$input['city'])->first()){
//                $data = Event::where('type_id',$type->id)->where('city',$city->id);
//            }


//        }else if (isset($input['type']) and isset($input['state'])){
//            if ($type = Type::where('name',$input['type'])->first() and
//            $state = State::where('name',$input['state'])->first())
//            $data = Event::where('type_id',$type->id)->where('state',$state);


//        }else if (isset($input['type'])){
//            $type = Type::where('name',$input['type'])->first();
//            $data =  $type->events;
//        }
        if ($input['type']) {
            $type = Type::where('name', $input['type'])->first();
            if (isset($type)) {
                $data = $type->events;
            }
        }

        return $data;
    }


    protected function searchCity($input = [])
    {
        if (isset($input['city'])) {
            if ($city = City::where('name', $input['city'])->first()) {
                $data = $city->events;
            } else {
                $data = new \Illuminate\Database\Eloquent\Collection;
            }
        }
        return $data;
    }

    protected function searchState($input = [])
    {
//        $data='';
        if (isset($input['state'])) {
            if ($city = State::where('name', $input['state'])->first()) {
                $data = $city->events;
            } else {
                $data = new \Illuminate\Database\Eloquent\Collection;
            }
        }
        return $data;
    }

    protected function searchClub($input = [])
    {
        $data = new \Illuminate\Database\Eloquent\Collection;
        if (isset($input['club'])) {

            if ($events = Event::where('name', 'LIKE', '%' . $input['club'] . '%')->get()) {

                $data = $events;

                // $data = collect($event_s);

//
            } else {
                $data = new \Illuminate\Database\Eloquent\Collection;
            }
        }
        return $data;

    }


    protected function getKeys($input)
    {
        $params = explode(',', $input);
        $array = array_intersect($this->suported, $params);
        $keys = array_keys($array);
        return $keys;
    }

    protected function getFilters($input)
    {
        $data = [];
        $params = explode(',', $input);
        $array = array_intersect($this->supportedFilters, $params);
        foreach (array_keys($array) as $filter) {
            $filter = [$filter => 1];
            $data = array_merge(
                $data, $filter
            );

        }
        return $data;
    }

// get or save subtypes
    protected function getType($input)
    {
        $id = [];
        if (empty($input)) {
            return null;
        } else {
//            return $input;
            $types = explode('،', $input);
            for ($i = 0; $i < count($types); $i++) {
                $type = DB::table('gangs')->where('name', $types[$i])->first();

                if (empty($type)) {
                    $type1 = new Gang;
                    $type1->name = $types[$i];
                    $type1->save();

                    $id[] = $type1->id;
                } else {

                    $id[] = $type->id;

                }

            }
            return $id;
        }
    }

    //

    protected function getCity($request)
    {
        $newcity = $request->city;
        $city = city::where('name', $newcity)->first();
        if (empty($city)) {
            $city = City::create(['name' => $newcity, 'state_id' => $request['state']]);
            return $city->id;
        } else {
            return $city['id'];
        }
    }

    //
    protected function handleAvatar($input)
    {
        if ($file = $input->file('avatar')) {
            $file_name = time() . '.' . $file->getClientOriginalName();
            $path_original = storage_path($this->getImageFoldersName('photos/events/event_profile/original/') . $file_name);
            $path_thumbnail = storage_path($this->getImageFoldersName('photos/events/event_profile/thumbnail/') . $file_name);
            $path_medium = storage_path($this->getImageFoldersName('photos/events/event_profile/medium/') . $file_name);
            Image::make($file)->fit(150, 150)->save($path_thumbnail);
            Image::make($file)->fit(300, 300)->save($path_medium);
            Image::make($file)->save($path_original);
            $photo = Eventphotos::create(['path' => $file_name]);
            return $photo->id;
        } else {
            return null;
        }
    }

    public function updateAvatar($input, $event)
    {

        // $user = User::findOrFail($user_id);

        if ($id = $event->avatar) {
            $photo = Eventphotos::find($id);
            $file_name = $photo->path;
            $path_original = storage_path('photos/events/event_profile/original/' . $this->getImageFoldersName(null, $photo->created_at) . $file_name);
            $path_thumbnail = storage_path('photos/events/event_profile/thumbnail' . $this->getImageFoldersName(null, $photo->created_at) . $file_name);
            $path_medium = storage_path('photos/events/event_profile/medium' . $this->getImageFoldersName(null, $photo->created_at) . $file_name);


            unlink($path_thumbnail);
            unlink($path_original);
            unlink($path_medium);
            $photo->delete();
        }
        if ($file = $input->file('avatar')) {
            $file_name = time() . '.' . $file->getClientOriginalName();
            $path_original = storage_path($this->getImageFoldersName('photos/events/event_profile/original') . $file_name);
            $path_thumbnail = storage_path($this->getImageFoldersName('photos/events/event_profile/thumbnail') . $file_name);
            $path_medium = storage_path($this->getImageFoldersName('photos/events/event_profile/medium') . $file_name);
            Image::make($file)->fit(150, 150)->save($path_thumbnail);
            Image::make($file)->fit(300, 300)->save($path_medium);
            Image::make($file)->save($path_original);
            $photo = Eventphotos::create(['path' => $file_name]);
            return $photo->id;
        } else {
            return null;
        }


    }


    protected function handleTicketAvatar($input)
    {
        if ($file = $input->file('avatar')) {
            $file_name = time() . '.' . $file->getClientOriginalName();
            $path_original = storage_path($this->getImageFoldersName('photos/events/ticket/original/') . $file_name);
            $path_thumbnail = storage_path($this->getImageFoldersName('photos/events/ticket/thumbnail/') . $file_name);
            $path_medium = storage_path($this->getImageFoldersName('photos/events/ticket/medium/') . $file_name);
            Image::make($file)->fit(150)->save($path_thumbnail);
            Image::make($file)->fit(300)->save($path_medium);
            Image::make($file)->save($path_original);
            $photo = Ticketavatar::create(['path' => $file_name]);
            return $photo->id;
        } else {
            return null;
        }
    }

    // $user = User::findOrFail($user_id);
    protected function updateTicketAvatar($input, $ticket)
    {
        if ($id = $ticket->avatar_id) {
            $photo = Ticketavatar::find($id);
            $file_name = $photo->path;
            $path_original = storage_path('photos/events/ticket/original/' . $this->getImageFoldersName(null, $photo->created_at) . $file_name);
            $path_thumbnail = storage_path('photos/events/ticket/thumbnail' . $this->getImageFoldersName(null, $photo->created_at) . $file_name);
            $path_medium = storage_path('photos/events/ticket/medium' . $this->getImageFoldersName(null, $photo->created_at) . $file_name);


            unlink($path_thumbnail);
            unlink($path_original);
            unlink($path_medium);
            $photo->delete();
        }
        if ($file = $input->file('avatar')) {
            $file_name = time() . '.' . $file->getClientOriginalName();
            $path_original = storage_path($this->getImageFoldersName('photos/events/ticket/original/') . $file_name);
            $path_thumbnail = storage_path($this->getImageFoldersName('photos/events/ticket/thumbnail/') . $file_name);
            $path_medium = storage_path($this->getImageFoldersName('photos/events/ticket/medium/') . $file_name);
            Image::make($file)->fit(150)->save($path_thumbnail);
            Image::make($file)->fit(300)->save($path_medium);
            Image::make($file)->save($path_original);
            $photo = Ticketavatar::create(['path' => $file_name]);
            return $photo->id;
        } else {
            return null;
        }
    }

    protected function handleTimeLineImage($input)
    {
        if ($file = $input->file('photo')) {
            $file_name = time() . '.' . $file->getClientOriginalName();
            $path_original = storage_path($this->getImageFoldersName('photos/timeline/original/') . $file_name);
            $path_thumbnail = storage_path($this->getImageFoldersName('photos/timeline/thumbnail/') . $file_name);
            $path_medium = storage_path($this->getImageFoldersName('photos/timeline/medium/') . $file_name);
            Image::make($file)->fit(150, 150)->save($path_thumbnail);
            Image::make($file)->fit(300, 300)->save($path_medium);
            Image::make($file)->save($path_original);
            $photo = TimelinePhoto::create(['path' => $file_name]);
            return $photo->id;
        } else {
            return null;
        }
    }

    /**
     *
     * @param $event
     * @param $subscribers
     * @return array
     */
    protected function ticketStruct($tickets)
    {
        foreach ($tickets as $ticket) {
//            foreach ($ticket->managers as $manager){
//                $managerData [] =array(
//                    'name'=>$manager->name,
//                    'avatar'=>$manager->avatar ? $manager->avatar->path : null,
//                );
//            }
//            if ($timelines = $ticket->timeLine){
//                $timelineData = array();
//                foreach ($timelines as $timeline){
//                    $timelineData[] = [
//                        'id'=>$timeline->id,
//                        'name'=>$timeline->name ? $timeline->name : null,
//                        'text'=>$timeline->text ? $timeline->text: null,
//                        'time'=>$timeline->time ? $timeline->time : null,
//                        'photo'=>$timeline->photo_id ? $timeline->photo->path : null,
//                        'created_at'=>$timeline->created_at ? $timeline->created_at : null,
//                    ];
//                }
//            }
            $data[] = array(
                'id' => $ticket->id,
                'name' => $ticket->name,
//                'body'=>$ticket->body,
                'avatar' => $ticket->avatar_id ? $this->getImageFoldersName(null, $ticket->avatar->created_at) . $ticket->avatar->path : null,
                'price' => $ticket->price ? $ticket->price : 'free',
//                'state'=>$ticket->state ? $ticket->State->name : null,
//                'city'=>$ticket->city ? $ticket->City->name : null,
//                'managers'=>$ticket->managers->pluck('name'),
//                'timeLine'=>$ticket->timeLine ? $timelineData : null,
            );
        }
        if (empty($data)) {
            return null;
        }
        return $data;
    }

    protected function eventManagers($event)
    {
        $data = null;
        $managers = $event->tourManagers;
        foreach ($managers as $manager) {
            $data[] = [
                'manager_name' => $manager->name,
                'avatar' => $manager->avatar_id ? $manager->avatar->path : null,
            ];
        }
        return $data;
    }

    protected function deleteAvatar($event)
    {

        // $user = User::findOrFail($user_id);

        if ($id = $event->avatar) {
            $photo = Eventphotos::find($id);
            $file_name = $photo->path;
            $path_original = storage_path('photos/events/event_profile/original/' . $this->getImageFoldersName(null, $photo->created_at) . $file_name);
            $path_thumbnail = storage_path('photos/events/event_profile/thumbnail' . $this->getImageFoldersName(null, $photo->created_at) . $file_name);
            $path_medium = storage_path('photos/events/event_profile/medium' . $this->getImageFoldersName(null, $photo->created_at) . $file_name);


            unlink($path_thumbnail);
            unlink($path_original);
            unlink($path_medium);
            $photo->delete();
        }
    }

    protected function getImageFoldersName($directory = null, $date = "nothing")
    {
        if ($date == "nothing") {
            $date = Carbon::now();
        } else {
            $date = Carbon::parse($date);
        }

        if ($directory == null) {
            return ('/' . $date->year . '/' . $date->month . '/' . $date->day . '/');
        }

        if (!file_exists(storage_path($directory . '/' . $date->year))) {
            mkdir(storage_path($directory . '/' . $date->year));
        }
        if (!file_exists(storage_path($directory . '/' . $date->year . '/' . $date->month))) {
            mkdir(storage_path($directory . '/' . $date->year . '/' . $date->month));
        }
        if (!file_exists(storage_path($directory . '/' . $date->year . '/' . $date->month . '/' . $date->day))) {
            mkdir(storage_path($directory . '/' . $date->year . '/' . $date->month . '/' . $date->day));
        }
        return ($directory . '/' . $date->year . '/' . $date->month . '/' . $date->day . '/');
    }

    protected $supportedFilters = [
        'request' => 'permission',
        'gender' => 'gender',
        'hide_event' => 'private',
    ];
    protected $suported = [
        'city' => 'city',
        'state' => 'state',
        'type' => 'type',
    ];

    /**
     * @param $eid
     * @return array|string
     */
    public function deleteEvent($eid)
    {
        $event = Event::find($eid);
        if (empty($event)) {
            return 'no event';
        }
        $user = Auth::user();

        if (!($event->tourManagers->first()->id == $user->id)) {
            return 'no access';
        }
        //delete picture of event
        if ($event->avatar) {
            $this->deleteAvatar($event);
        }
       $event->tourManagers()->detach();
        $event->subscribers()->detach();
        $posts = $event->posts;
        $postAvatarId = [];


        // return $postAvatarId;
        if ($tickets = $event->tickets) {
            $timeLines = [];
           // $ticketManager=[];
            $ticketAvatarId = [];
            $timeLineAvatarId = [];
            $ticketId=[];
            foreach ($tickets as $ticket) {
                $ticketAvatarId[] = $ticket->avatar_id;
                $ticketId[]=$ticket->id;

                foreach ($ticket->timeLine as $item) {

                    $timeLines[] = $item;
                    $timeLineAvatarId[] = $item->photo_id;
                }


            }
            DB::table('ticketmanager')->whereIn('ticket_id',$ticketId)->delete();
            foreach ($posts as $post) {
                $postAvatarId[] = $post->media_id;
                $post->comments()->delete();
                $post->likes()->detach();
                $post->delete();
            }
            //delete picture of posts of event;
            if (!empty($postAvatarId)) {
                $this->deleteAvatarPosts($postAvatarId);
            }
            if (!empty($timeLineAvatarId)) {
                //delete picture of timeLines of tickets of event
                $this->deleteAvatarTimeline($timeLineAvatarId);
            }
            if (!empty($ticketAvatarId)) {
                //delete picture of ticket of event
                $this->deleteAvatarTicket($ticketAvatarId);
            }

            if (!empty($timeLines)) {
                foreach ($timeLines as $item) {
                    $item->delete();
                }

            }


            if (!empty($tickets)) {
                foreach ($tickets as $item) {
                    $item->delete();
                }

            }
//

        }
     $event->delete();
        return "event delete";

    }

    protected function deleteAvatarTimeline($images)
    {
        $images = array_diff($images, [null]);
        if (empty($images) !== true) {
            $timeline_photos = DB::Table('timeline_photos')->whereIn('id', $images)->get();
            // return $timeline_photos;
            foreach ($timeline_photos as $timeline_photo) {
                $file = $timeline_photo->path;
                $path_original = storage_path('photos/timeline/original' . $this->getImageFoldersName(null, $timeline_photo->created_at) . $file);
                // return $path_original;
                $path_thumbnail = storage_path('photos/timeline/thumbnail' . $this->getImageFoldersName(null, $timeline_photo->created_at) . $file);
                $path_medium = storage_path('photos/timeline/medium' . $this->getImageFoldersName(null, $timeline_photo->created_at) . $file);
                unlink($path_original);
                unlink($path_thumbnail);
                unlink($path_medium);

                DB::Table('timeline_photos')->where('id', $timeline_photo->id)->delete();
            }
        }
    }

    protected function deleteAvatarTicket($images)
    {
        $images = array_diff($images, [null]);
        if (empty($images) !== true) {
            $ticket_photos = DB::Table('ticketavatars')->whereIn('id', $images)->get();


            foreach ($ticket_photos as $ticket_photo) {
                $file_name = $ticket_photo->path;
                $path_original = storage_path('photos/events/ticket/original/' . $this->getImageFoldersName(null, $ticket_photo->created_at) . $file_name);
                $path_thumbnail = storage_path('photos/events/ticket/thumbnail' . $this->getImageFoldersName(null, $ticket_photo->created_at) . $file_name);
                $path_medium = storage_path('photos/events/ticket/medium' . $this->getImageFoldersName(null, $ticket_photo->created_at) . $file_name);


                unlink($path_thumbnail);
                unlink($path_original);
                unlink($path_medium);
                DB::Table('ticketavatars')->where('id', $ticket_photo->id)->delete();
            }
        }
    }

    protected function deleteAvatarPosts($images)
    {
        $images = array_diff($images, [null]);
        if (empty($images) !== true) {
            $posts_photos = DB::Table('postmedia')->whereIn('id', $images)->get();


            foreach ($posts_photos as $post_photo) {
                $media_name = $post_photo->name;

                $path_original = storage_path('photos/posts/original/' . $this->getImageFoldersName(null, $post_photo->created_at) . $media_name);
                $path_medium = storage_path('photos/posts/medium/' . $this->getImageFoldersName(null, $post_photo->created_at) . $media_name);
                $path_thumbnail = storage_path('photos/posts/thumbnail/' . $this->getImageFoldersName(null, $post_photo->created_at) . $media_name);
                unlink($path_original);
                unlink($path_medium);
                unlink($path_thumbnail);
            }
            DB::Table('postmedia')->where('id', $post_photo->id)->delete();
        }


    }

    public function deleteTicket($tid){
        $ticket = Ticket::find($tid);
        if (empty($ticket)) {
            return 'no ticket';
        }
        $event=$ticket->event;
        $user = Auth::user();

        if (!($event->tourManagers->first()->id == $user->id)) {
            return 'no access';
        }
        $image=$ticket->avatar_id;
        $array= (array)$image;
       $this->deleteAvatarTicket($array);

        DB::table('ticketmanager')->where('ticket_id',$tid)->delete();
        $timeLineAvatarId=[];
        foreach ($ticket->timeLine as $item) {

            $timeLines[] = $item;
            $timeLineAvatarId[] = $item->photo_id;
        }
        if (!empty($timeLineAvatarId)) {
            //delete picture of timeLines of tickets of event
            $this->deleteAvatarTimeline($timeLineAvatarId);
        }
        if (!empty($timeLines)) {
            foreach ($timeLines as $item) {
                $item->delete();
            }

        }
        $ticket->delete();
        return "ticket delete";
    }
    public function activeTickets($activeSkip=0 , $activeLimit=5 ){
      $data=[];
        $activeTicket=Ticket::whereDate('start_date', '>', Carbon::today()->toDateString())->skip((int)$activeSkip)->take((int)$activeLimit)->latest('updated_at')->get();
      foreach($activeTicket as $item){
          $data[]=['id'=>$item->id,
                    'name'=>$item->name,
                    'avatar' => $item->avatar_id ? $this->getImageFoldersName(null, $item->avatar->created_at) . $item->avatar->path : null,
                    'club'=>$item->event_id?$item->event->name :null,
                    'startDate'=>$item->date,
                    'endDate'=>$item->end_date

              ];
      }
        return $data;
    }

}