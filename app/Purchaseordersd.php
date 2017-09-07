<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchaseordersd extends Model
{
    protected $table='purchaseordersd';
    protected $fillable = ['poDate','totalAmount','discount','customer_id','isGenerate','user_id','created_at','updated_at'];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function products()
    {
    	return $this->belongsToMany('App\Product','purchaseordersd_product')->withPivot('qty','unitPrice','amount','user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function stockoutsds(){
        return $this->hasOne(Stockoutsd::class);
    }
}
