<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    /*
    //this model is provide filters to let people see or access another event
    //currently has 3  filter
    //1- gender and is a boolean and if
    gender ==null there is no filter on gender
    /////////////////////////////////////////////
    2 - population and its a integer table if
    population dont be null based on number should count and answer the request based on that
    basicly count how many is subscribers and reply to the user who requested
    but we can add this part to tickets and let events have as much as users want to have
    /////////////////////////////////////////////
    3 - request is a boolean and if
    is 0 mean there is no request
    is 1 mean should reqest

    for handleing the request part we have to make a pivot table between users and event with another table culled status
    status is a boolean type and if be 1 means he got accepted and should send notification for user
    if the status be 0 means he got rejected  ( on the user site we should keep this part edditable so if the tour manager decided he want accept the use he can)

    */
    protected $fillable = [
        'filterable_id','filterable_type','gender','limit','request','hide_event'
    ];

    public  function filterable(){
        return $this->morphTo();
    }


    public function event(){
        return $this->belongsTo('App\Event');
    }

    public function requests(){
        return $this->hasMany('App\Requesthandeling');
    }
}
