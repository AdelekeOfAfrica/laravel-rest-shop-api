<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\order;
use App\Models\orderitem;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Transformers\OrderItemsTransformer;

class checkoutcontroller extends Controller
{
   use Helpers;

    public function index($order_id){
        if($user=auth()->user()){
            $user_id =auth()->user()->id;
             
            $order_rec =order::where('user_id',$user_id)->get();
            if($order_rec){
                $order_det=orderitem::where('orders_id',$order_id)->get();
                $total_price =orderitem::where('orders_id',$order_id)->sum('price');
                return response()->json([
                    "total_price"=>$total_price
                ]);
                return $this->response->collection($order_det,new OrderItemsTransformer);
 
                
            }

        }else{
            return response()->json(["
            status"=>403,
            "message"=>"kindly login first"
        ]);
        }
    }
    
    public function store(Request $request){
        if($user = auth()->user()){
            $validator = Validator::make($request->all(),[
                'firstname'=>'required|string|min:4|max:30',
                'lastname'=>'required|string|min:4|max:30',
                'phone'=>'required|string|min:4|max:30',
                'email'=>'required|string|min:4|max:30',
                'address'=>'required|string|min:4|max:255',
                'city'=>'required|string|min:4|max:255',
                'state'=>'required|string|min:4|max:255',
                "zipcode"=>'required|string|min:4|max:255',
                
            ]);

            if($validator->fails()){
                return response()->json(['errors'=>$validator->errors()]);
            }
            else{
                $user_id = auth()->user()->id;
                $order = new order;
                $order->user_id =$user_id;
                $order->firstname = $request->firstname;
                $order->lastname = $request->lastname;
                $order->phone = $request->phone;
                $order->email = $request->email;
                $order->address = $request->address;
                $order->city = $request->city;
                $order->state = $request->state;
                $order->zipcode = $request->zipcode;

                $order->payment_mode = "POD";
                $order->tracking_no="marzdjservice".rand(1111,9999);
                $order->save();

                $cart =cart::where('user_id',$user_id)->get();
                $orderitems =[];
                foreach($cart as $item){
                    $orderitems[] =[
                        'product_id'=>$item->product_id,
                        'qty'=>$item->product_qty,
                        'price'=>$item->product->regular_price
                    ];

                    $item->product->update([
                        'qty'=>$item->product->qty - $item->product_qty
                    ]);

                }

                $order->orderitems()->createMany($orderitems);
                cart::destroy($cart);

                return response()->json([
                    'status'=>200,
                    'message'=>'order placed successfully'
                ]);
            }
        }
        else{
            return response()->json([
                'status'=>403,
                'message'=>'oops you forgot to login'
            ]);
        }
    }
}
