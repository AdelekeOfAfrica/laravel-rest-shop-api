<?php 
namespace App\Transformers;
use App\Models\order;
use League\Fractal\TransformerAbstract;
use App\Transformers\OrderItemsTransformer;


class OrderTransformer extends TransformerAbstract{
    public function transform(order $or){
        return[
            'id'=>(int) $or->id,
            'user_id'=>$or->user_id,
            'firstname'=>$or->firstname,
            'lastname'=>$or->lastname,
            'phone'=>$or->phone,
            'email'=>$or->email,
            "address"=>$or->address,
            "city"=>$or->city,
            "state"=>$or->state,
            "zipcode"=>$or->zipcode

        ];
    }
    public function includeorderitem(order $or){
        return $this->item($or->orderitems, new OrderItemsTransformer);
    }
   
 
}
