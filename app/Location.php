<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table='locations';
    protected $fillable=['writer','latitude','longitude','about','location','address'];

    public function photos(){
        return $this->hasMany('App\LocatePhoto','location_id');
    }
    public function comments()
    {
        return $this->hasMany('App\LocateComment','location_id');
    }


}
