<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tmpproductre extends Model
{
    protected $table='tmpproductre';

    protected $fillable = ['product_id','qty','user_id'];
     public function product()
    {
    	return $this->belongsTo('App\Product','product_id');
    }
}
