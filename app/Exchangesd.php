<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exchangesd extends Model
{
    protected $table='exchangesd';
    protected $fillable=['stockoutsd_id','purchaseordersd_id'];



    public function stockoutsd(){
        return $this->belongsTo(Stockoutsd::class,'stockoutsd_id');
    }
    public function purchaseordersd(){
        return $this->belongsTo(Purchaseordersd::class,'purchaseordersd_id');
    }

    public function products(){
        return $this->belongsToMany(Product::class)->withTimestamps()->withPivot('qty','expd');
    }
    public function brand(){
        return $this->belongsTo(Brand::class,'brand_id');
    }
}
