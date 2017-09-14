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

    public  function imports(){
        return $this->belongsToMany(Import::class)->withTimestamps()->withPivot('product_id','qty','expd','status');
    }

    public function exchange(){
        return $this->hasOne(Exchange::class);
    }

    public function returnpro(){
        return $this->hasOne(Returnpro::class);
    }

}