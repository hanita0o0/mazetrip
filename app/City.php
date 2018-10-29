<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $fillable=[
        'name','state_id'
    ];


    public function events(){
        return $this->hasMany('App\Event','city');
    }
}
