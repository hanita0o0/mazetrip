<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
this table is a post for users :
* ==> users can make posts related to events or related to themselves
* relationships :
* post->user as likes
* post->user as maker
* post->photos as visual content ( this photos should save as photo that belongs to user not event)
* posts->comments as comments
* posts->event as belongs to what event ( can be nullable )
*/
class Post extends Model
{
    //
    protected $fillable = [
        'body','user_id','event_id','media_id','location_id'
    ];

    public  function photo(){
        return $this->belongsTo('App\Postmedia','media_id' );
    }
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }


    public function likes(){
        return $this->belongsToMany('App\User','likes');
    }

    public function event(){
        return $this->belongsTo('App\Event','event_id');
    }
    public function comments(){
        return $this->hasMany('App\comment');
    }

}
