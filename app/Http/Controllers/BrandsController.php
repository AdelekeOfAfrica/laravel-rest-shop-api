<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Brands;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Transformers\BrandTransformer;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BrandsController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($storeId)
    {
        //'stores'->is the name of table 
        //function($query) is the name used in the middleware
        $brands = Brands::whereHas('stores',function($query) use($storeId){
                $query->where('store_id',$storeId);
        })->get();// to get all the user stores 

        if(empty($brands)){
            throw new NotFoundHttpException('store does not exist ');
        }
        return $this->response->collection($brands, new BrandTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $storeId)
    {
       $validator = Validator::make($request->all(),[
           'name'=>'required|string|min:4|max:30',
           'details'=>'required|string|max:255'
       ]);

       if($validator->fails()){
           return response()->json(['errors'=>$validator->error()],400);
       }
       try {
           $stores = Store::findOrFail($storeId)->brands()->create($request->all());

       }catch(HttpException $th){
           throw $th;
       }

       $response = [
           'id'=>$stores->id,
           'message'=>'brand created successfully'
       ];

       return response()->json($response)->setStatusCode(200);
     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brands  $brands
     * @return \Illuminate\Http\Response
     */
    public function show($storeId, $id)
    {
        //always use this when you have middleware 
        $brands = Brands::whereHas('stores',function($query) use($storeId){
            $query->where('store_id',$storeId);
    })->findOrFail($id);// to get all the user stores 

    if(empty($brands)){
        throw new NotFoundHttpException('brand does not exist ');
    }
    return $this->response->item($brands, (new BrandTransformer)->setDefaultIncludes(['store']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brands  $brands
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $storeId,$id)
    {
        //

        $brands = Brands::whereHas('stores',function($query) use($storeId){
            $query->where('store_id',$storeId);
        })->findOrFail($id);

        if(empty($brands)){
            throw new NotFoundHttpException('brands not found');
        }

        if (!empty($request->name) ){
                
            $validator = Validator::make($request->all(),[
                'name'=>'required|string|min:3|max:30',

            ]);
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }
            $brands->name = $request->name;
        }

        if (!empty($request->details) ){
            
            $validator = Validator::make($request->all(),[
                'details'=>'required|string|max:255',

            ]);
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }
            $brands->details = $request->details;
        }
        if($brands->isDirty()){
            $brands->save();

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
     * @param  \App\Models\Brands  $brands
     * @return \Illuminate\Http\Response
     */
    public function destroy($storeId,$id)
    {
        //

        $brands = Brands::whereHas('stores',function($query) use($storeId){
            $query->where('store_id',$storeId);
        })->findOrFail($id);

        if(empty($brands)){
            throw new NotFoundHttpException('brands not found');
        }

        try{
            $brands->delete();

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
