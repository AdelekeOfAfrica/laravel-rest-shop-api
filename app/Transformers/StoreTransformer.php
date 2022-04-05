<?php

namespace App\Transformers;

use App\Models\User;
use App\Models\Store;
use App\Transformers\UserTransformer;
use League\Fractal\TransformerAbstract;

class StoreTransformer extends TransformerAbstract{

    public function transform(Store $store){
        return[

            'id'=>(int) $store->id,
            'names'=>$store->name,
            'details'=>$store->details,

        ];
    }

    public function includeOwner(Store $store)
    {
      return  $this->item($store->owner, new UserTransformer);
    }

    public function includeAdmins(Store $store)
    {
      return  $this->collection($store->users, new UserTransformer);
    }

}