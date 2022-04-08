<?php 
namespace App\Transformers;
use App\Models\cart;
use League\Fractal\TransformerAbstract;


class CartTransformer extends TransformerAbstract{
    public function transform(cart $cat){
        return[
            'id'=>(int) $cat->id,
            'user_id'=>$cat->user_id,
            'product_id'=>$cat->product_id,
            'product_qty'=>$cat->product_qty

        ];
    }
    public function includeproduct(cart $cat){
        return $this->item($cat->product, new ProductTransformer);
    }
   
 
}
