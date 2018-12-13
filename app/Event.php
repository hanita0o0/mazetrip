<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //


    protected $fillable = [
        'name','header','about', 'about_team' , 'is_active' , 'type_id' , 'avatar' , 'state' , 'city','activation_no','location_id'
    ];

    public function save(array $options = [])
    {
        if (empty($this->activation_nu)){
            $this->activation_no = str_random(15);
        }
        return parent::save($options);
    }


    public function favoriteByUsers(){
        return $this->belongsToMany('App\User','event_user');
        /**
         * this function is for reciving all notifications and know all changes
         */
    }

    public function ban(){
        return $this->morphOne('App\Ban','banable');
    }

    public function tourManagers(){
        return $this->belongsToMany('App\User','touremanagers')->withTimestamps();
    }

    public function subscribers(){
        return $this->belongsToMany('App\User','subscribers')->withPivot('updated_at')->withTimestamps();
        /**
         * this function is like the like and follow button
         * to follow a event to know whats going on in there
         */
    }

    public function Chat(){
        return $this->morphOne('App\Chat','chatable');
        //every event have one chat and the chat gets its users from the subscribers and tour managers
    }

    public function Filter(){
        return $this->morphOne('App\Filter','filterable');
    }

    public function tickets() {
        return $this->hasMany('App\Ticket');
    }

    public function timeLines(){
        return $this->morphMany('App\Timeline' , 'timelineable');
    }

    public function types(){
        return $this->belongsToMany('App\Type');
    }

    public function avatarImage(){
        return $this->belongsTo('App\Eventphotos','avatar');
    }

    public function checkList(){
        return $this->hasMany('App\Checklist');
    }
    public function posts(){
        return $this->hasMany('App\Post','event_id');
    }
    public function State(){
        return $this->belongsTo('App\State','state');
    }
    public function City(){
        return $this->belongsTo('App\City','city');
    }
    public function gangs(){
        return $this->belongsToMany('App\Gang');
    }


}
