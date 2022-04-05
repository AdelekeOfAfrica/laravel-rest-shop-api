<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use App\Transformers\PermissionTransformer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminPermissionsController extends Controller
{
    //
    use Helpers;
    public function permission($id){

    
        if(!$user = User::findOrFail($id)){
            throw new NotFoundHttpException('user not found');
        }

        return $this->response->collection($user->getAllPermissions(), new PermissionTransformer()); 
    }
}
