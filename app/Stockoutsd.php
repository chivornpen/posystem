<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stockoutsd extends Model
{
    protected $table='stockoutsd';
    protected $fillable=['stockoutDate','purchaseordersd_id','user_id'];

    public  function purchaseordersd(){
        return $this->belongsTo(Purchaseordersd::class);
    }

    public  function subimports(){
        return $this->belongsToMany(Subimport::class)->withTimestamps()->withPivot('product_id','qty','expd','status');
    }
}
