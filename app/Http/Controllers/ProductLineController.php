<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Brands;
use App\Models\productLine;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Validator;
use App\Transformers\ProduclLineTransformer;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductLineController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        $pl = productLine::all();

        if(empty($pl)){
        throw new NotFoundHttpException('productline does not exist ');
        }
        return $this->response->collection($pl, (new ProduclLineTransformer)->setDefaultIncludes(['brands']))->setStatusCode(200);

    }

    


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        //
        $validator = Validator::make($request->all(),[
            'tshirt'=>'required|string|min:4|max:30',
            'shirt'=>'required|string|min:4|max:255',
            'shoe'=>'required|string|min:4|max:255',
            'pant'=>'required|string|min:4|max:255'
        ]);
 
        if($validator->fails()){
            return response()->json(['errors'=>$validator->error()],400);
        }
        try{
            

            $pl = Brands::findOrFail($id)->productLines()->Create($request->all());
            
                
            
        }catch(JWTException $th){
            throw new $th;
        }
 
        $response = [
            'id'=>$pl->id,
            'message'=>'brand created successfully'
        ];
 
        return response()->json($response)->setStatusCode(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\productLine  $productLine
     * @return \Illuminate\Http\Response
     */
    public function show($storeId,$id)
    {
        //$pl = productLine::findOrFail($id);

        $pl = productLine::findOrFail($id);


        if(empty($pl)){
            throw new NotFoundHttpException('these product line is not found ');
        }

        return $this->response->item($pl, (new ProduclLineTransformer)->setDefaultIncludes(['brands']))->setStatusCode(200);
       
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\productLine  $productLine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
       $pl = productLine::findOrFail($id);

        if(empty($pl)){
            throw new NotFoundHttpException('product line not found');
        }

        if (!empty($request->tshirt) ){
                
            $validator = Validator::make($request->all(),[
                'tshirt'=>'required|string|min:3|max:30',

            ]);
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }
            $pl->tshirt = $request->tshirt;
        }

        if (!empty($request->shirt) ){
            
            $validator = Validator::make($request->all(),[
                'shirt'=>'required|string|max:255',

            ]);
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }
            $pl->shirt = $request->shirt;
        }
        if($pl->isDirty()){
            $pl->save();

            $response =[
                'message'=>'producl line Updated Successfully',
                'id'=>$id
            ];

            return response()->json($response, 200);

        }
        return response()->json(['message'=>'nothing to update '], 200);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\productLine  $productLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(productLine $productLine,$id)
    {
        //
         $pl = productLine::findOrFail($id);

        if(empty($pl)){
            throw new NotFoundHttpException('product line could  not be deleted ');
        }

        try{
            $pl->delete();

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
