<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    //
    protected $fillable =['path'];


    public function avatar(){
        return $this->hasOne('App\User','avatar_id');
    }
    public  function PhotoOwners(){
        return $this->belongsToMany('App\User','photo_user');
    }
     public function type(){
        return $this->hasOne('App\Type','photo_id');
    }

}
