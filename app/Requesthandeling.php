<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requesthandeling extends Model
{
    //
    protected $fillable = [
        'user_id','filter_id','status'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     *
     * show this request belong to which use and
     * for more information
     */
    public function User(){
        return $this->belongsTo('App\User','user_id');
    }

    public function filter(){
        return $this->belongsTo('App\Filter');
    }
}
