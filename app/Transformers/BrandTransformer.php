<?php 
namespace App\Transformers;
use App\Models\Store;
use App\Models\Brands;
use App\Transformers\UserTransformer;
use League\Fractal\TransformerAbstract;

class BrandTransformer extends TransformerAbstract{
    public function transform(Brands $brands){
        return[
            'id'=>(int) $brands->id,
            'name'=>$brands->name,
            'details'=>$brands->details

        ];
    }

    public function includeStore(Brands $brands){
        return $this->collection($brands->stores->first(), new StoreTransformer);
    }   
}
