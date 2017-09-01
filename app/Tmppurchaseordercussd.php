<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tmppurchaseordercussd extends Model
{
    protected $table='tmppurchaseordercussd';
    protected $fillable = ['product_id','qty','unitPrice','amount','user_id','created_at','updated_at'];
    public function product()
    {
    	return $this->belongsTo(Product::class);
    }
}
