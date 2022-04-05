<?php

namespace App\Http\Controllers;
use App\Models\product;
use App\Models\productLine;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Transformers\ProductTransformer;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
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
        $product = product::all();
       // return $product;
        return $this->response->collection($product, (new ProductTransformer)->setDefaultIncludes(['produclLine']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|min:4|max:30',
            'short_description'=>'required|string|min:4|max:255',
            'description'=>'required|string|min:4|max:255',
            "sku"=>'required|string|min:4|max:255',
            "stock_status"=>'required|string|min:4|max:255',
            "quantity"=>'required|string|min:4|max:255',
            
        ]);
 
        if($validator->fails()){
            return response()->json(['errors'=>$validator->error()],400);
        }
        try{

            //for a single file 
            //$images=$request->file('images');//these is what you will use to fetch the image 
            //if($request->hasFile('image)){
            // $new_name = rand().'.'.$image->getClientOriginalExtension();
            // $image->move(public_path('/products/images'),$new_name);
            // $imageName=$imageName.$new_name.",";
           // }
            
            $images=$request->file('images');//name that will be used in postman is images[]
            $imageName='';
            foreach($images as $image){
                $new_name = rand().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('/products/images'),$new_name);
                $imageName=$imageName.$new_name.",";
            }
            $imagedb=$imageName;


            $pl = productLine::findOrFail($id)->products()->Create
            ([
                'name'=>$request->name,
                'slug'=>$request->name,
                "short_description"=>$request->short_description,
                "description"=>$request->description,
                "regular_price"=>$request->regular_price,
                "sku"=>$request->sku,
                "stock_status"=>$request->stock_status,
                "quantity"=>$request->quantity,
                "images"=>$imagedb
            ]);
                
                
            
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
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($storeId,$id)
    {
        //
        $product = product::findOrFail($id);
        return $this->response->item($product,(new ProductTransformer)->setDefaultIncludes(["produclLine"]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id,$storeId)
    {
        //
        $product = product::findOrFail($id);

        if(empty($product)){
            throw new NotFoundHttpException('product line not found');
        }

        if (!empty($request->name) ){
                
            $validator = Validator::make($request->all(),[
                'name'=>'required|string|min:3|max:30',

            ]);
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }
            $product->name = $request->name;
        }

        if (!empty($request->slug )){
            
            $validator = Validator::make($request->all(),[
                'slug'=>'required|string|max:255',

            ]);
            
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }
            $product->slug = $request->slug;
        }
        if($product->isDirty()){
            $product->save();

            $response =[
                'message'=>'product has been  Updated Successfully',
                'id'=>$id
            ];

            return response()->json($response, 200);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(product $product,$id,$storeId)
    {
        $pl = product::findOrFail($id);

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
