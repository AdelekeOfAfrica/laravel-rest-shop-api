<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Transformers\StoreTransformer;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StoreController extends Controller
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
        if(! $user = Auth()->user()){
            throw new NotFoundHttpException('user not found');
        }
        $store = Store::where('owner_id',$user->id)->get();

        return $this->response->collection($store, (new StoreTransformer)->setDefaultIncludes(['owner','admins']))->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $rules =
        [
            'name'=>[
                'required',
                'string',
                'min:3',
                'max:30',
                'unique:stores,name'
            ],
            'details'=>[
                'required',
                'email',
                'max:255'
                
            ],
        ];

        $validator= Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                'validation errors' =>$validator->errors()
            ]);
        }
        if(! $user = Auth()->user()){
            throw new NotFoundHttpException('user not found');
        }

        $user->stores()->create([
            'name'=>$request->name,
            'details'=>$request->details
        ]);
        $response =[
            'message'=>'stores Created Successfully',
            'id'=>$stores->id
        ];

        return response()->json($response)->setstatusCode(200);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(! $user = Auth()->user()){
            throw new NotFoundHttpException('user not found');
        }
        $store = Store::where('owner_id',$user->id)->findOrFail($id);

        return $this->response->item($store, (new StoreTransformer)->setDefaultIncludes(['owner','admins']))->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStoreRequest  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        
        if(! $user = Auth()->user()){
            throw new NotFoundHttpException('user not found');
        }
        $store = Store::where('owner_id',$user->id)->findOrFail($id);

        if(!$store){
            throw new NotFoundHttpException('Store does not exist');
        }

        if (!empty($request->name) ){
            
            $validator = Validator::make($request->all(),[
                'name'=>'required|string|min:3|max:30|unique:stores,name',

            ]);
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }
            $store->name = $request->name;
        }

        if (!empty($request->details) ){
            
            $validator = Validator::make($request->all(),[
                'details'=>'required|string|max:255',

            ]);
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }
            $store->details = $request->details;
        }
        if($store->isDirty()){
            $store->save();

            $response =[
                'message'=>'stores Updated Successfully',
                'id'=>$id
            ];

            return response()->json($response, 200);

        }
        return response()->json(['message'=>'nothing to update '], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(! $user = Auth()->user()){
            throw new NotFoundHttpException('user not found');
        }
        $store = Store::where('owner_id',$user->id)->findOrFail($id);

        if(! $store){
            throw new NotFoundHttpException('Store does not exist');
        }
        try{
            $store->delete();

            $response = [
                'message'=>'stores deleted successfully',
                'id'=>$id
            ];
            return $response()->json($response,200);

        }catch(HttpException $th){
            throw $th;
        }
    }
    
}
