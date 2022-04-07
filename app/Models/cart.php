<?php

namespace App\Models;

use App\Models\product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class cart extends Model
{
    use HasFactory;
    protected $table="carts";
    protected $fillable = ["user_id","product_id","product_qty"];

    public function product(){
        return $this->belongsTo(product::class,'product_id','id');
    }
}
