<?php

namespace App\Models;

use App\Models\product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class category extends Model
{
    use HasFactory;
    protected $fillable =["name","slug"];

    public function products(){
        return $this->belongsToMany(product::class,'category_store');
    }
}
