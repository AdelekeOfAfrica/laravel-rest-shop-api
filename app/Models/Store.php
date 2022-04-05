<?php

namespace App\Models;

use App\Models\User;
use App\Models\Brands;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;
    protected $fillable=["name","details"];

    public function owner(){
        return $this->belongsTo(User::class,'owner_id');
    }

    public function users(){
        return $this->belongsToMany(User::class,'store_admin');//these is basically for the store_admins
    }

    public function brands(){
        return $this->belongsToMany(Brands::class,'brand_store');
    }
  
}
