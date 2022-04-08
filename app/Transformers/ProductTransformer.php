<?php 
namespace App\Transformers;
use App\Models\product;
use App\Models\category;
use League\Fractal\TransformerAbstract;
use App\Transformers\CategoryTransformer;


class ProductTransformer extends TransformerAbstract{
    public function transform(product $product){
        return[
            'id'=>(int) $product->id,
            'name'=>$product->name,
            'slug'=>$product->slug,
            "short_description"=>$product->short_description,
            "description"=>$product->description,
            "regular_price"=>$product->regular_price,
            "sku"=>$product->sku,
            "stock_status"=>$product->stock_status,
            "quantity"=>$product->quantity,
            "images"=>$product->images


        ];
       
    }
    public function includecategory(product $product){
        return $this->item($product->category, new CategoryTransformer);
    }
}
