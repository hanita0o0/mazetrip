<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    //

    protected $fillable = [
        'name','text','time','event_id','photo_id'
    ];



    public  function  event(){
        return $this->belongsTo('App\Event','event_id');
    }

    public function photo(){
        return $this->belongsTo('App\Eventphotos','photo_id');
    }

    public function morphable(){
        return $this->morphTo('timelineable');
    }
}
