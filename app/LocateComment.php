<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocateComment extends Model
{
    //
    protected $table='locatecomments';
    protected $fillable=['body','location_id','star','author'];
    public function location()
    {
        return $this->belongsTo('App\Location','location_id');
    }
}
