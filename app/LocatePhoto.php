<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocatePhoto extends Model
{
    //
    protected $table='locatePhotos';
    protected $fillable=['path','location_id'];
    public function location(){
        return $this->belongsTo('App\Location','location_id');
    }
}
