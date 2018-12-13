<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    //
    protected $fillable = [
       'name','date','body','event_id','filter_id','activation_num','filter_id','price','state','city','address','avatar_id','end_date','is_active','location_id'
    ];

    /*
     * the ticket might have a relation between filter
     * the relation is polymorphic and every ticket connect to one filter and after that handeling the filter parts
     */
    public function filter(){
        return $this->morphOne('App\Filter','filterable');
    }

    public function user(){
        return $this->belongsToMany('App\User');
    }

    //TODO :: have to create timeline for ticket
    public function timeLine(){
        return $this->morphMany('App\Timeline' , 'timelineable');
    }

    public function event(){
        return $this->belongsTo('App\Event');
    }
    public function managers(){
        return $this->belongsToMany('App\User','ticketmanager','ticket_id','user_id')->withPivot('role');
    }

    public function City(){
        return $this->belongsTo('App\City','city');
    }

    public function State(){
        return $this->belongsTo('App\State','state');
    }

    public function avatar(){
        return $this->belongsTo('App\Ticketavatar','avatar_id');
    }

}
