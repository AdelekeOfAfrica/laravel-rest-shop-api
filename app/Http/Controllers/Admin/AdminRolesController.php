<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminRolesController extends Controller
{
    use Helpers;
   public function roles($id){

    
        if(!$user = User::findOrFail($id)){
            throw new NotFoundHttpException('user not found');
        }

        return $user->getRoleNames(); 

    }

    public function changeRole(Request $request, $id){
        if(!$user = User::findOrFail($id)){
            throw new NotFoundHttpException('user not found');
        }

         try{
             $user->syncRoles([$request->role]);
         }
         catch(HttpException $th){
             throw $th;
         }

         return response()->json(['message'=>'users roles updated'])->setStatusCode(200);
    }

   
}
