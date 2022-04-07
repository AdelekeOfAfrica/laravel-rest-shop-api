<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\category;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Transformers\CategoryTransformer;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
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
        $category=category::all();

       // return $this->response->collection($category, (new CategoryTransformer)->setDefaultIncludes(["product"]))->setStatusCode(200);
        return $this->response->collection($category, new CategoryTransformer)->setStatusCode(200);
     
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
        $validator = Validator::make($request->all(),[
            
            'name'=>'required|string|min:3|max:30',
            'slug'=>'required|string|min:3|max:30',
            ]);
 
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }
        try{
            $category = new category;
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->save();

    
            
                
            
        }catch(JWTException $th){
            throw new $th;
        }
 
        $response = [
            'id'=>$category->id,
            'message'=>'brand created successfully'
        ];
 
        return response()->json($response)->setStatusCode(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($storeId,$id)
    {
        //
        $category = category::findOrFail($id);


        if(empty($category)){
            throw new NotFoundHttpException('these product line is not found ');
        }

        return $this->response->item($category, (new CategoryTransformer)->setDefaultIncludes(['product']))->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$storeId,$id)
    {
        //
        $category = category::findOrFail($id);

        if(empty($category)){
            throw new NotFoundHttpException('product line not found');
        }

        if (!empty($request->name) ){
                
            $validator = Validator::make($request->all(),[
                'name'=>'required|string|min:3|max:30',

            ]);
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }
            $category->name = $request->name;
        }

        if (!empty($request->slug) ){
            
            $validator = Validator::make($request->all(),[
                'slug'=>'required|string|max:255',

            ]);
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }
            $category->slug = $request->slug;
        }
        if($category->isDirty()){
            $category->save();

            $response =[
                'message'=>'category Updated Successfully',
                'id'=>$id
            ];

            return response()->json($response, 200);

        }
        return response()->json(['message'=>'nothing to update '], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($storeId,$id)
    {
        //
        $category = category::findOrFail($id);

        if(empty($category)){
            throw new NotFoundHttpException('sorry these category could  not be deleted ');
        }

        try{
            $category->delete();

            $response = [
                'message'=>'stores deleted successfully',
                'id'=>$id
            ];
            return response()->json($response,200);

        }catch(HttpException $th){
            throw $th;
        }
    }
}
