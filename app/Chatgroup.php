<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chatgroup extends Model
{
    //
    protected $fillable =[
        'name','information',
    ];


    public function chat(){
        return $this->morphOne('App\Chat','chatable');
    }

    public function filter(){
        return $this->morphOne('App\Filter','filterable');
    }

    public function members(){
        return $this->belongsToMany('App\User','chatgroupmembers')->withPivot('updated_at','admin')->withTimestamps();
    }

}
