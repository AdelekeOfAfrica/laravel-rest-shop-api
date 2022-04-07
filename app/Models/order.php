<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $table = "orders";
    protected $fillable = [
        "user_id","firstname","lastname","phone","email","address","city",
        "state","zipcode","payment_id","payment_mode","tracking_no","status"
    ];

    public function orderitems(){
        return $this->hasMany(orderitem::class,'orders_id','id');
    }
}
