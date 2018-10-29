<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;
//    protected $guard ='api_2';
    protected $fillable = [
        'name', 'email', 'password','state_id','city_id','address','phone','gender','bio','about','role_id','subscriber','avatar_id','cover_id','activation_no','api_token','login'
    ];


    public function save(array $options = [])
    {
        if (empty($this->api_token)){
            $this->api_token = bin2hex(random_bytes(32));
        }
        return parent::save($options);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
//        'remember_token',
        'password'
    ];

    public function toureManagers(){
        return $this->belongsToMany('App\Event','touremanagers');
    }

    public  function tickets(){
        return  $this->belongsToMany('App\ticket');
    }

    public function state(){
        return $this->belongsTo('App\State' );
    }

    public function city(){
        return $this->belongsTo('App\city');
    }
   public function roles(){
        return $this->belongsToMany('App\Role');
    }

    public function avatar(){
        return $this->belongsTo('App\Photo','avatar_id');
    }
    public function cover(){
        return $this->belongsTo('App\Photo','cover_id');
    }

    public function gallery(){
        return $this->belongsToMany('App\Photo','photo_user');
    }

    public function likes(){
        return $this->belongsToMany('App\Post','likes');
    }

    public function requests(){
        return $this->hasMany('App\Requesthandeling');
    }


    public function followers(){
        return $this->belongsToMany('App\User','followers','user_id','follower_id')->withTimestamps();
    }

    public function following(){
        return $this->belongsToMany('App\User','followers','follower_id','user_id')->withTimestamps();
    }

    //if the user making its user private;
    public function filter(){
        return $this->morphOne('App\Filter','filterable');
    }

    public function posts(){
        return $this->hasMany('App\Post','user_id');
    }

    public function events(){
        return $this->belongsToMany('App\Event','subscribers')->withTimestamps();
    }

    public function chatgroups(){
        return $this->belongsToMany('App\Chatgroup','chatGroupMembers')->withPivot('user_id');
    }



    public function blockedUsers(){
        return $this->morphOne('App\Ban','banable');
    }
    ////////////////////////////////////////////////////////////////////

    public function setPasswordAttribute($value){
   // $this->attributes['password']= encrypt($value);
    $this->attributes['password'] = Hash::make($value);
    }

   //public function getPasswordAttribute($value){
    //  return decrypt($value);
   // }


}
