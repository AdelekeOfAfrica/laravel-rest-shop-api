<?php

namespace App\Models;

use App\Models\User;
use App\Models\Store;
use App\Models\productLine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brands extends Model
{
    use HasFactory;

    protected $fillable = ["name","details"];

    public function stores(){
        return $this->belongsToMany(Store::class,'brand_store');
    }

    public function productLines(){
        return $this->belongsToMany(productLine::class,'product_line_store');
    }

    

    
}
