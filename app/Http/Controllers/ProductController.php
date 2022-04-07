<?php

namespace App\Http\Controllers;
use App\Models\product;
use App\Models\category;
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
        //return $this->response->collection($product, (new ProductTransformer)->setDefaultIncludes(['produclLine']));
        return $this->response->collection($product, new ProductTransformer)->setStatusCode(200);
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
            'category_id'=>'required',
            'brand'=>'required|string|min:3|max:30',
            'name'=>'required|string|min:4|max:30',
            'slug'=>'required|string|min:4|max:30',
            'short_description'=>'required|string|min:4|max:255',
            'description'=>'required|string|min:4|max:255',
            'regular_price'=>'required',
            "sku"=>'required|string|min:4|max:255',
            "stock_status"=>'required|string|min:4|max:255',
            "quantity"=>'required|string|min:1|max:255',
            
        ]);
 
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
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
            $imagedb='/products/images/'.$imageName;

            $product = new product;
            $product->category_id = $request->category_id;
            $product->brand = $request->brand;
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->short_description = $request->short_description;
            $product->description = $request->description;
            $product->regular_price = $request->regular_price;
            $product->sku = $request->sku;
            $product->stock_status = $request->stock_status;
            $product->quantity = $request->quantity;
            $product->images = $imagedb;
            $product->save();
                
                
            
        }catch(JWTException $th){
            throw new $th;
        }
 
        $response = [
            'id'=>$product->id,
            'message'=>'product created successfully'
        ];
 
        return response()->json($response)->setStatusCode(200);
    }

    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $product = product::findOrFail($id);
        //return $this->response->item($product,(new ProductTransformer)->setDefaultIncludes(["produclLine"]));
        return $this->response->item($product, new ProductTransformer)->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
        $validator = Validator::make($request->all(),[
            'category_id'=>'required',
            'brand'=>'required|string|min:4|max:30',
            'name'=>'required|string|min:4|max:30',
            'slug'=>'required|string|min:4|max:30',
            'short_description'=>'required|string|min:4|max:255',
            'description'=>'required|string|min:4|max:255',
            'regular_price'=>'required',
            "sku"=>'required|string|min:4|max:255',
            "stock_status"=>'required|string|min:4|max:255',
            "quantity"=>'required|string|min:1|max:255',
            
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }
        try{
            $product = product::findOrFail($id);
            $product->category_id = $request->category_id;
            $product->brand = $request->brand;
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->short_description = $request->short_description;
            $product->description = $request->description;
            $product->regular_price = $request->regular_price;
            $product->sku = $request->sku;
            $product->stock_status = $request->stock_status;
            $product->quantity = $request->quantity;
            if($request->hasFile('images')){
                $path = $product->images;
                if(file::exists($path)){
                    file::delete($path);
                    $images=$request->file('images');//name that will be used in postman is images[]
            $imageName='';
            foreach($images as $image){
                $new_name = rand().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('/products/images'),$new_name);
                $imageName=$imageName.$new_name.",";
            }
            $imagedb='/products/images/'.$imageName;
                }
            }
            $product->update();
                
                
            
        }catch(JWTException $th){
            throw new $th;
        }
 
        $response = [
            'id'=>$product->id,
            'message'=>'product created successfully'
        ];
 
        return response()->json($response)->setStatusCode(200);
 
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(product $product,$id)
    {
        $product = product::findOrFail($id);

        if(empty($pl)){
            throw new NotFoundHttpException('product could  not be deleted ');
        }

        try{
            $product->delete();

            $response = [
                'message'=>'product deleted successfully',
                'id'=>$id
            ];
            return response()->json($response,200);

        }catch(HttpException $th){
            throw $th;
        }
    }

    public function product($slug){
        $category =category::where('slug',$slug)->first();

        if($category){
            $product =product::where('category_id',$category->id)->get();
            if($product){
                    
                $response = [
                    'message'=>'product  found successfully',
                    'id'=>$id
                ];
                return response()->json($response,200);
            }
            else{
                $response = [
                    'message'=>'product not found',
                    'id'=>$id
                ];
                return response()->json($response,200);  
            }
            
        }
        else{
            $response = [
                'message'=>'category not found',
                'id'=>$id
            ];
            return response()->json($response,200);  
        }
    }

    

    public function productcat($category_slug,$product_slug){
        $category =category::where('slug',$category_slug)->first();

        if($category){
            $product =product::where('category_id',$category->id)->where('slug',$product_slug)->get();
            if($product){
                    
                $response = [
                    'message'=>'product found ',
                    'personal_data'=>[
                        'product'=>$product,
                        'category'=>$category
                    ],
                ];
                return response()->json($response,200);
            }
            else{
                $response = [
                    'message'=>'product not found',
                    'id'=>$product
                ];
                return response()->json($response,200);  
            }
            
        }
        else{
            $response = [
                'message'=>'category not found'
                
            ];
            return response()->json($response,200);  
        }
    }

    

    
}
