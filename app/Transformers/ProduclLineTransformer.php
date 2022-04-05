<?php
namespace App\Transformers;
use App\Models\User;
use App\Models\productLine;
use App\Transformers\UserTransformer;
use App\Transformers\BrandTransformer;
use League\Fractal\TransformerAbstract;

 class ProduclLineTransformer extends TransformerAbstract{

   public function transform(productLine $pl){
        return[
            'id'=>(int) $pl->id,
            't-shirt'=>$pl->tshirt,
            'shirt'=>$pl->shirt,
            'shoe'=>$pl->shoe,
            'pant'=>$pl->pant
            
        ];
    }

    //public function includeUser(productLine $pl){
    //return $this->collection($pl->users, new UserTransformer());
    //} 

    public function includeBrands(productLine $pl){
        return $this->collection($pl->brands, new BrandTransformer);
    }

   

}