<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use  Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{
    //
    public function login(Request $request){
        $rules =
        [
            'email'=>[
                'required',
                'email',
                'max:255'
              
            ],
            'password'=>[
                'required',
                'string',
                Password::min(8)->mixedCase()
                ->numbers()
                ->symbols()
                
            ],
        ];

        $validator= Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                'validation errors' =>$validator->errors()
            ]);
        }

        $credencials = $request->only('email','password');

        try
        {
             if(!$token = auth()->attempt($credencials))
             {
                 throw new unauthorizedHttpException('username or password is not valid ');
             }
        }
        catch(JWTException $e)
        {
            throw $e;
        }

        return $this->respondWithToken($token);

    }

    public function refresh(){
        try{
            if(!$token = auth()->getToken())
            {
                throw  new NotFoundHttpException('token does not exist');
            }
            return $this->respondWithToken(auth()->refresh($token));
        }catch(JWTException $e){
            throw $e;
        }
    }

    public function logout(){
        try{
            auth()->logout();
        }
        catch(JWTException $e){
            throw $e;
        }

        return response()->json([
            'message'=>'user logged out successfully'
        ]);
       
    }
    private function respondWithToken($token)
    {
        return response()->json([
            'access_token'=> $token,
            'token_type'=>'bearer',
            'expires_in' =>auth()->factory()->getTTL() * 60,
        ]);
    }

}
