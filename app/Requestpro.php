<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requestpro extends Model
{
    protected  $table='requestpro';
    protected $fillable=['reqDate',	'reqby', 'user_id','status','recordnum'];
    public function products()
    {
    	return $this->belongsToMany('App\Product','product_requestpro')->withPivot('qty','user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function returnreqpro(){
        return $this->hasOne(Returnreqpro::class);
    }
}
