<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchaseordersd_Product extends Model
{
    protected $table='purchaseordersd_product';
    protected $fillable = ['product_id','qty','unitPrice','amount','user_id','created_at','updated_at'];
}
