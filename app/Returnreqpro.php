<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Returnreqpro extends Model
{
    protected  $table='returnreqpros';
    protected $fillable=['stockoutre_id',	'requestpro_id', 'returnBy','status','isGenerate'];


    public function stockoutre(){
        return $this->belongsTo(Stockoutre::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function products(){
        return $this->belongsToMany(Product::class)->withTimestamps()->withPivot('qtyreturn','qtyorder');
    }
    public function requestpro(){
        return $this->belongsTo(Requestpro::class,'returnreqpro_id');
    }
}
