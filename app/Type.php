<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //
    protected $fillable=['name','description','cover_id','avatar_id'];

    public function photo(){
        return $this->belongsTo('App\Photo','photo_id');
    }

    public function cover(){
        return $this->belongsTo('App\Photo','cover_id');
    }

    public function events(){
        return $this->belongsToMany('App\Event');
    }


}
