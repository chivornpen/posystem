<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    protected $table='exchanges';
    protected $fillable=['stockout_id','purchaseorder_id'];



    public function stockout(){
        return $this->belongsTo(Stockout::class,'stockout_id');
    }
    public function purchaseorder(){
        return $this->belongsTo(Purchaseorder::class,'purchaseorder_id');
    }

    public function products(){
        return $this->belongsToMany(Product::class)->withTimestamps()->withPivot('qty','expd');
    }
}
