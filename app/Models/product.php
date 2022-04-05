<?php

namespace App\Models;

use App\Models\category;
use App\Models\productLine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class product extends Model
{
    use HasFactory;
    protected $fillable=[
    "name","slug","short_description","description",
    "regular_price","sku","stock_status","quantity","images"];


    public function productLine(){
      return $this->belongsToMany(productLine::class,'product_match');
    }
    public function category(){
      return $this->belongsToMany(category::class,'category_store');
    }
}
