<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;


class UserTransformer extends TransformerAbstract {
    public function  transform (User $user){
        return[
            'id'=>$user->id,
            'email'=>$user->email,
            'username'=>$user->name,
            'firstname'=>$user->userProfile ? $user->userProfile->firstname: 'unknown',
            'lastname' =>$user->userProfile ? $user->userProfile->lastname: 'unknown',
            'gender' =>$user->userProfile ? $user->userProfile->gender: 'none',

        ];
    }

 
}
 