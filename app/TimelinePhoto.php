<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimelinePhoto extends Model
{
    //
    protected $fillable = ['path'];
    public function photo(){
        return $this->hasMany('App\Timeline','photo_id');
    }
}
