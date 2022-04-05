<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
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
        if(!$users = User::with('roles')->get()){
            throw new NotFoundHttpException('users not found');
        }
        return $this->response->collection($users, new UserTransformer())->setStatusCode(200);
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
            'email'=>'required|email|unique:users,email|max:30',
            'name'=>'required|string|min:3|max:30',
            'firstname'=>'required|string|min:3|max:30',
            'lastname'=>'required|string|min:3|max:30',
            'gender'=>'required|string'
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }

        try{
            $user=User::firstOrcreate([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>'Test@1234'
            ]);

            $user->userProfile()->updateOrCreate(['user_id'=>$user->id],
            [
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
            'gender'=>$request->gender,
            'active'=>true,
            ]);

            
            $response=[
                'message'=>'user profile created  successfully',
                'id'=>$user->id,
        
                ];
        
         $user->assignRole('customer');

        }catch(JWTException $th){
            throw $th;
        }

        
    return response()->json($response, 201);
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        if(!$user = User::findOrFail($id)){
            throw new NotFoundHttpException('user profile with id='.$id);
        }
        return $this->response->item($user, new UserTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        if(! $user = User::find($id)){
            throw new NotFoundHttpException('user profile with id='.$id);
        }
        if(!empty($request->firstname)){
        $validator = Validator::make($request->all(),[
            'firstname'=>'required|string|min:3|max:30'
        ]);
    }

        
        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }

        $user->userProfile()->updateOrCreate(['user_id'=>$user->id],
        [
        'firstname'=>$request->firstname,
       
        ]); 

        if(!empty($request->lastname)){
            $validator = Validator::make($request->all(),[
                'lastname'=>'required|string|min:3|max:30'
            ]);
        }

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }

        $user->userProfile()->updateOrCreate(['user_id'=>$user->id],[
            'lastname'=>$request->lastname,

        ]);
        $response=[
            'message'=>'user profile updated  successfully',
            'id'=>$user->id,

        ];

        return response()->json($response, 201);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if(!$user = User::findOrFail($id)){
            throw new NotFoundHttpException('user not found');
        }

        try{
            $user->delete();
        }catch(HttpException $e){
            throw $e;
        }

        return response()->json(['message'=>'user deleted successfully','id'=>$id]);


    }

    public function suspend($id)
    {
        if(!$user = User::findOrFail($id)){
            throw new NotFoundHttpException('user not found');
        }
        try{
            $user->userProfile->updateOrCreate(['user_id'=>$id],[
                'active'=>false
            ]);
        }catch(HttpException $th){
            throw $th;
        }
        $response=[
            'message'=>'user suspended successfully',
            'id'=>$id
        ];
        return response()->json($response, 200);
    }

    public function activate($id){
        if(!$user = User::findOrFail($id)){
            throw new NotFoundHttpException('user not found');
        }
        try{
            $user->userProfile->updateOrCreate(['user_id'=>$id],[
                'active'=>true
            ]);
        }catch(HttpException $th){
            throw $th;
        }
        $response=[
            'message'=>'user activated successfully',
            'id'=>$id
        ];
        return response()->json($response, 200);
    
    }
}
