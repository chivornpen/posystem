<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $table="imports";
    protected $fillable =['impDate','invoiceDate', 'invoiceNumber',	'totalAmount',	'discount',	'supplierId', 'userId'];

    public  function products(){
        return $this->belongsToMany(Product::class,'import_product','importId','productId')->withTimestamps()->withPivot('qty', 'landingPrice', 'mfd', 'expd');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplierId');
    }
    public function histories(){
        return $this->hasMany(History::class,'importId','id');
    }
    public  function stockouts(){
        return $this->belongsToMany(Stockout::class)->withTimestamps()->withPivot('product_id','qty','expd','status');
    }

    public function stockoutres(){
        return $this->belongsToMany(Stockoutre::class)->withTimestamps()->withPivot('product_id','qty','expd','status');
    }
}
