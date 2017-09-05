<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tmpeditpurchaseordercussd extends Model
{
    protected $table='tmpeditpurchaseordercussd';
    public $timestamps = false;

    protected $fillable = ['purchaseorder_id','product_id','qty','unitPrice','amount','user_id','recordStatus'];
     public function product()
    {
    	return $this->belongsTo(Product::class);
    }
}
