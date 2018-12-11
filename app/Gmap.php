<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gmap extends Model
{
    //
    protected $table='gmaps_geocache';
    protected $fillable=['address','latitude','longitude'];
    public function posts(){
        return $this->hasMany('App\Post','location_id');
    }
    public function tickets(){
        return $this->hasMany('App\Ticket','location_id');
    }
    public function events(){
        return $this->hasMany('App\Event','location_id');
    }
}
