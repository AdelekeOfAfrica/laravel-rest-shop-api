<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class isStoreOwner
{
    use Helpers;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        //does the store request belongs to the right owner
        $storeId = $request->route('store');

        if(!$user =Auth()->User()){
            throw new NotFoundHttpException('User not found');
        }

        if(! $user->hasRole('store_owner')){
            throw new AccessDeniedHttpException('User does not have the right to access these page ');
        }
        $exists = $user->stores()
                    ->where(function($query) use ($storeId, $user){
                        $query->where('owner_id',$user->id);

                        if($storeId){
                            $query->where('id',$storeId);
                        }
                    })->exists();

        if(!$exists){
            throw new AccessDeniedHttpException('these is not your store and you do not have the right to access these page ');
        }
        return $next($request);
    }
}
