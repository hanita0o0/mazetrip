<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    //
    protected $fillable = [
        'chatable_id','chatable_type'
    ];


    public function chatable(){
        return $this->morphTo('chatable');
    }

    //every chat has many messages
    public function messages(){
        return $this->hasMany('App\Message','chat_id');
    }

        public function event(){
            return $this->belongsTo('App\Event','event_id');
        }


    public function blockedUsers(){
        return $this->morphOne('App\Ban','banable');
    }


}
