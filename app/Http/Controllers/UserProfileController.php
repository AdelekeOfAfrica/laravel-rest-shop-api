<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\StoreUserProfileRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserProfileController extends Controller
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
        try{
            $user = auth()->user();
                
        }catch(JWTException $th){
            throw $th;
        }

        return $this->response->item($user, new UserTransformer)->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserProfileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(),[
            'firstname'=>'required|string|min:3|max:30',
            'lastname'=>'required|string|min:3|max:30',
            'gender'=>'required'

        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }
        try{

            if(!$user = auth()->user()){
                throw new NotFoundException('users not found');
            }

            $user->userProfile()->updateOrCreate(['user_id'=>$user->id],
            [
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
            'gender'=>$request->gender,
            'active'=>true,
            ]); 

        }catch(JWTException $th){
            throw $th;
        }

        $response=[
            'message'=>'user profile created  successfully',
            'id'=>$user->id,

        ];

        return response()->json($response, 201);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserProfileRequest  $request
     * @param  \App\Models\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //

        try{

            if(!$user = auth()->user()){
                throw new NotFoundException('users not found');
            }
            //checking of firstname
            if(!empty($request->firstname))
            {
                $validator = Validator::make($request->all(),[
                    'firstname'=>'required|string|min:3|max:30', ]);
            }

            if($validator->fails()){
                return response()->json(['errors'=>$validator->errors()]);
            }

            $user->userProfile()->updateOrCreate(['user_id'=>$user->id],
            [
            'firstname'=>$request->firstname,
           
            ]); 


           //checking for the lastname if not empty 
            if(!empty($request->lastname))
            {
                $validator = Validator::make($request->all(),[
                    'lastname'=>'required|string|min:3|max:30', ]);
            }

            if($validator->fails()){
                return response()->json(['errors'=>$validator->errors()]);
            }

            $user->userProfile()->updateOrCreate(['user_id'=>$user->id],
            [
            'lastname'=>$request->lastname,
           
            ]);

        }catch(JWTException $th){
            throw $th;
        }

        $response=[
            'message'=>'user profile updated successfully',
            'id'=>$user->id,

        ];

        return response()->json($response, 201);
    }

        
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
        try{
            $user = auth()->user();
            $user->delete();
            auth()->logout();
        }
        catch(JWTException $th){
             throw $th;
        }

        $response=[
            'message'=>'user profile deleted successfully',
            'id'=>$user->id,

        ];

        return response()->json($response, 201);
    }
}
