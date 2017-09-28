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

    public function exchangesd(){
        return $this->hasOne(Exchangesd::class,'stockoutsd_id');
    }
    public  function subimports(){
        return $this->belongsToMany(Subimport::class,'import_stockoutsd')->withTimestamps()->withPivot('product_id','qty','expd','status');
    }
    public function brand(){
        return $this->belongsTo(Brand::class,'brand_id');
    }
}
