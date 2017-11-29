<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chartaccount extends Model
{
    //
    protected $table = 'chartaccounts';
    protected $fillable=['accountcode', 'typeaccount_id', 'description','drsign','crsign'];


    public  function typeaccount(){
        return $this->belongsTo(Typeaccount::class);
    }
    
    public function transection(){
        return $this->hasMany(Transection::class);
    }
}
