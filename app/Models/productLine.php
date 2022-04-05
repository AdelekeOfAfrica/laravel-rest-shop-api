<?php

namespace App\Models;

use App\Models\User;
use App\Models\Brands;
use App\Models\product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class productLine extends Model
{
    use HasFactory;

    protected $fillable =["tshirt","shirt","shoe","pant"];

   

    public function brand(){
        return $this->belongsToMany(Brands::class,'product_line_store');//product_line_store name of table 
    }

    public function products(){
        return $this->belongsToMany(product::class,'product_match');
    }

   

    
}

