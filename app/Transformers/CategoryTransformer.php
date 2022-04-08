<?php 
namespace App\Transformers;
use App\Models\category;
use League\Fractal\TransformerAbstract;


class CategoryTransformer extends TransformerAbstract{
    public function transform(category $cat){
        return[
            'id'=>(int) $cat->id,
            'name'=>$cat->name,
            'slug'=>$cat->slug

        ];
    }
   
 
}
