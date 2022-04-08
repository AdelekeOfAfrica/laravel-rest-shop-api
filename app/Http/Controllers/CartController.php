<?php

namespace App\Http\Controllers;
use App\Models\cart;
use App\Models\User;
use App\Models\product;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Auth;
use App\Transformers\CartTransformer;

class CartController extends Controller
{
    use Helpers;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if($user=auth()->user()){
            $user_id = auth()->user()->id;
            $cart=cart::where('user_id',$user_id)->get();
           return $this->response->collection($cart,(new CartTransformer)->setDefaultIncludes(['product']));
        }else{
            return response()->json([
                'status'=>403,
                'message'=>'you do not have rite to this page '
            ]);

        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if($user = auth()->user()){
           $user_id = auth()->user()->id;
           $product_id = $request->product_id;
           $product_qty = $request->product_qty;

           $productcheck = product::where('id',$product_id)->first();
           if($productcheck){
               if(cart::where('product_id',$product_id)->where('user_id',$user_id)->exists()){
                   return response()->json([
                       'status'=>409,
                       'message'=>$productcheck->name, 'product already added to the cart'
                   ]);
               }
               else{
                   $cartitem = new cart;
                   $cartitem->user_id = $user->id;
                   $cartitem->product_id = $product_id;
                   $cartitem->product_qty = $product_qty;
                   $cartitem->save();
                   
                return response()->json([
                    'status'=>200,
                    "message"=>"product added to cart "
                ]);
               }
           }
           else{
               return response()->json([
                   'status'=>409,
                   'message'=>'product does not exist'
               ]);
           }
        }
        else {
            return response()->json([
                "message"=>"user needs to be logged in first"

            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update($cart_id,$scope)
    {
        //
        if($user=auth()->user()){
            $user_id = auth()->user()->id;
            $cartitem=cart::where('id',$cart_id)->where('user_id',$user_id)->first();
            if($scope =="inc"){
                $cartitem->product_qty += 1;
            }
            else if($scope =="dec"){
                $cartitem->product_qty -= 1;
            }
            $cartitem->update();
            return response()->json([
                'status'=>200,
                'message'=>"quantity updated successfully"
            ]);

            return response()->json([
                'status'=>200,
                'message'=>"quantity updated successfully"
            ]);
        

        }else{
            return response()->json([
                'status'=>403,
                'message'=>"you have to login first"
            ]);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy($cart_id)
    {
        //

        if($user=auth()->user()){
            $user_id = auth()->user()->id;
            $cartitem=cart::where('id',$cart_id)->where('user_id',$user_id)->first();
           if($cartitem){
               $cartitem->delete();
               return response()->json([
                'status'=>403,
                'message'=>"cart deleted"
            ]);
           }
        

        }else{
            return response()->json([
                'status'=>403,
                'message'=>"you have to login first"
            ]);
        }
        
    }
    
}
