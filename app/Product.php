<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $table='products';
    protected $fillable = ['product_code','product_barcode','name','description','unitPrice','category_id','user_id','created_at','updated_at'];
    public function requestpros()
    {
        return $this->belongsToMany('App\Requestpro','product_requestpro')->pivot('product_id','qty','user_id');
    }
    public function purchaseorders()
    {
        return $this->belongsToMany('App\Purchaseorder','purchaseorder_product')->pivot('product_id','unitPrice','qty','amount','user_id');
    }
    public function purchaseordersds()
    {
        return $this->belongsToMany('App\Purchaseordersd','purchaseordersd_product')->pivot('product_id','unitPrice','qty','amount','user_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function tmpPurchaseOrders()
    {
        return $this->hasMany(TmpPurchaseOrder::class);
    }
    public function tmpEditPurchaseOrders()
    {
        return $this->hasMany(TpmEditPurchaseOrder::class);
    }
    public function tmpEditPurchaseordercussds()
    {
        return $this->hasMany(Tmpeditpurchaseordercussd::class);
    }
    public function Tmppurchaseordercussds()
    {
        return $this->hasMany(Tmppurchaseordercussd::class);
    }
    public function tmpproreqs()
    {
        return $this->hasMany('App\Tmpproreq');
    }
    public function pricelists()
    {
        return $this->hasMany(Pricelist::class);
    }
    public  function imports(){
        return $this->belongsToMany(Import::class,'import_product','importId','productId')->withTimestamps()->withPivot('qty', 'landingPrice', 'mfd', 'expd');
    }
    public function histories(){
        return $this->hasMany(History::class,'productId', 'id');
    }

    public  function subimports(){
        return $this->belongsToMany(Subimport::class,'subimport_product','subimport_id','product_id')->withTimestamps()->withPivot('qty','mfd', 'expd');
    }

    public function brands(){
        return $this->belongsToMany(Brand::class)->withTimestamps()->withPivot('qty');
    }

    public function exchanges(){
        return $this->belongsToMany(Exchange::class)->withTimestamps()->withPivot('qty','expd');
    }
    public function exchangesd(){
        return $this->belongsToMany(Exchangesd::class,'exchangesd_product','exchangesd_id','product_id')->withTimestamps()->withPivot('qty','expd');
    }

    public  function returnpros(){
        return $this->belongsToMany(Returnpro::class)->withTimestamps()->withPivot('qtyreturn','qtyorder');

    }
    public  function returnreqpros(){
        return $this->belongsToMany(Returnreqpro::class)->withTimestamps()->withPivot('qtyreturn','qtyorder');
    }
}