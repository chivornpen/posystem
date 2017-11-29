<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typeaccount extends Model
{
    protected $table = 'typeaccounts';
    protected $fillable= ['typeaccountcode','description','user_id'];

    public function chartaccounts(){
        return $this->hasMany(Chartaccounts::class);
    }
}
