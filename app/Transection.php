<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transection extends Model
{
    //

    protected $table= "transections";



    public function chartaccount(){
        return $this->belongsTo(Chartaccount::class);
    }
}
