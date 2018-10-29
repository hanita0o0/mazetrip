<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    //
    protected $fillable =[
        'banable_type','banable_id'
    ];

    public function banable(){
        return $this->morphTo('banable');
    }


}
