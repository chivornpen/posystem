<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table='brands';
    protected $fillable = ['brandCode','brandName','description','user_id','created_at','updated_at'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function products(){

        return $this->belongsToMany(Product::class)->withTimestamps()->withPivot('qty');
    }
}
