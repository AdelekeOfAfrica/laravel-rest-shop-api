<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderitem extends Model
{
    use HasFactory;
    protected $table = "orderitems";
    protected $fillable = [
        "orders_id","product_id","qty","price"
    ];
}
