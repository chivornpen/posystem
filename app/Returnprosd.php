<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Returnprosd extends Model
{
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
}
