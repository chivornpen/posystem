<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subimport extends Model
{
    protected $table = "subimports";
    protected $fillable=['subimportDate', 'purchaseorder_id', 'brand_id', 'supplier_id', 'imported_by'];


    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public  function user(){
        return $this->belongsTo(User::class,'imported_by');
    }
    public  function products(){
        return $this->belongsToMany(Product::class,'subimport_product','subimport_id','product_id')->withTimestamps()->withPivot('qty','mfd', 'expd');
    }
}
