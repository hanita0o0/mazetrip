<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $fillable = [
        'content' , 'user_id' , 'chat_id','parent_id','messagephoto_id'
    ];



    public function chat(){
        return $this->belongsTo('App\Chat');
    }

    public function photo(){
        return $this->belongsTo('App\Messagephotos','messagephoto_id');
    }

    public function writer(){
        return $this->belongsTo('App\User','user_id');
    }

    public function reply(){

        //need to work on this part
        // not working
        return $this->hasOne(self::class , 'parent_id');
    }
}
