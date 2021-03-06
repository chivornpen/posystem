<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Returnprosd extends Model
{
    // protected $dates = ['created_at', 'updated_at'];
    
    protected  $table='returnprosd';
    protected $fillable=['stockoutsd_id',	'purchaseordersd_id', 'returnBy','status','isGenerate'];


    public function stockoutsd(){
        return $this->belongsTo(Stockoutsd::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function products(){
        return $this->belongsToMany(Product::class)->withTimestamps()->withPivot('qtyreturn','qtyorder');
    }
    public function purchaseordersd(){
        return $this->belongsTo(Purchaseordersd::class,'purchaseordersd_id');
    }
    public function brand(){
        return $this->belongsTo(Brand::class,'brand_id');
    }
    public function getCreatedAtAttribute($value) {
        // dd();
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }
}
