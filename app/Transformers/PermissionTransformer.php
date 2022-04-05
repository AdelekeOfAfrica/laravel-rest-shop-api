<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;
use Spatie\Permission\Contracts\Permission;


class PermissionTransformer extends TransformerAbstract {
    public function  transform (Permission $permission){
        return[
            'id'=>$permission->id,
            'name'=>$permission->name,
            'username'=>$permission->name,
        ];
    }
}
 