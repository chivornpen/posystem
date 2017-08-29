<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stockout extends Model
{
    protected $table='stockouts';
    protected $fillable=['stockoutDate','purchaseorder_id','user_id'];

    public  function purchaseorder(){
        return $this->belongsTo(Purchaseorder::class);
    }

}