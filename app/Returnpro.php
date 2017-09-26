<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Returnpro extends Model
{
    protected  $table='returnpros';
    protected $fillable=['stockout_id',	'purchaseorder_id', 'returnBy','status','isGenerate'];


    public function stockout(){
        return $this->belongsTo(Stockout::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function products(){
        return $this->belongsToMany(Product::class)->withTimestamps()->withPivot('qtyreturn','qtyorder');
    }
    public function purchaseorder(){
        return $this->belongsTo(Purchaseorder::class,'purchaseorder_id');
    }
}
