<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stockoutre extends Model
{


    public function imports(){
        return $this->belongsToMany(Import::class)->withtimestamps()->withPivot('product_id','qty','expd','status');
    }
    public function returnreqpro(){
        return $this->hasOne(Returnreqpro::class);
    }
}
