<?php 
namespace App\Transformers;
use App\Models\category;
use League\Fractal\TransformerAbstract;
use App\Transformers\ProductTransformer;

class CategoryTransformer extends TransformerAbstract{
    public function transform(category $cat){
        return[
            'id'=>(int) $cat->id,
            'name'=>$cat->name,
            'slug'=>$cat->slug

        ];
    }
    public function includeProduct(category $cat){
        return $this->item($cat->products->first(), new ProductTransformer);
    }
 
}
