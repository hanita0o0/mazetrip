<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    //
    protected $fillable= ['user_id','body','post_id'];

//
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function post(){
        return $this->belongsTo('App\Post');
    }

//    public function writer(){
//        return $this->hasOne('App\User');
//    }
}
