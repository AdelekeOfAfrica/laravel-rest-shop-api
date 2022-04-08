<?php 
namespace App\Transformers;
use App\Models\orderitem;
use League\Fractal\TransformerAbstract;


class OrderItemsTransformer extends TransformerAbstract{
    public function transform(orderitem $oi){
        return[
            'id'=>(int) $oi->id,
            'orders_id'=>$oi->orders_id,
            'product_id'=>$oi->product_id,
            'qty'=>$oi->qty,
            'price'=>$oi->price

        ];
    }
   
 
}
