<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductLineController;
use App\Http\Controllers\Admin\AdminUserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api){

    $api->get('hello',function(){
    return 'hello Stores Api';
    });

    $api->post('/signup','App\Http\Controllers\UserController@store');
    $api->post('/login','App\Http\Controllers\auth\AuthController@login');


    $api->group(['prefix'=>'auth'], function($api){

        
        $api->post('/users/signup','App\Http\Controllers\UserController@store');
        $api->post('/users/login','App\Http\Controllers\auth\AuthController@login');

        //middleware for users to check their profile 
        $api->group(['middleware'=>'api.auth','prefix'=>'me'], function($api){//api is the guard  while auth/is the address u are going to use to het it 
            $api->get('/profile ','App\Http\Controllers\UserProfileController@index');
            $api->post('/profile','App\Http\Controllers\UserProfileController@store');
            $api->put('/profile','App\Http\Controllers\UserProfileController@store');
            $api->delete('/profile','App\Http\Controllers\UserProfileController@destroy');
            
        });



        //middleware for logged in users 
        $api->group(['middleware'=>'api.auth','prefix'=>'auth'], function($api){//api is the guard  while auth/is the address u are going to use to het it 
            $api->post('/token/refresh','App\Http\Controllers\auth\AuthController@refresh');
            $api->post('/logout','App\Http\Controllers\auth\AuthController@logout');
        });

    });



    $api->group(['middleware'=>['role:super_admin'],'prefix'=>'admin'], function($api){
       
        $api->resource('/users',AdminUserController::class);
        //to activate and suspend users
        $api->post('/users/{id}/activate','App\Http\Controllers\Admin\AdminUserController@suspend');
        $api->post('/users/{id}/suspend','App\Http\Controllers\Admin\AdminUserController@activate');
        // to show roles
        $api->get('/users/{id}/roles','App\Http\Controllers\Admin\AdminRolesController@roles');
        $api->get('/users/{id}/permissions','App\Http\Controllers\Admin\AdminPermissionsController@permission');
        // to change roles
        $api->post('/users/{id}/roles','App\Http\Controllers\Admin\AdminRolesController@changeRole');
        
    });
    //middleware for storeowners
    $api->group(['middleware'=>['role:store_owner'],'prefix'=>'stowners'],function($api){
        $api->post('stores','App\Http\Controllers\StoreController@store');//we created it here cause we restrict it 
        $api->group(['middleware'=>'isStoreOwner'],function($api){
            $api->resource('/stores',StoreController::class,['except'=>['store']]);//here 
            
            $api->resource('/stores/{store}/brands',BrandsController::class);
            
            
        });
        
    });

    //middleware for store_admin
    $api->group(['middleware'=>['role:store_admin'],'prefix'=>'stadmin'], function($api){
        $api->resource('/stores/{store}/productline', ProductLineController::class);
        $api->resource('/stores/{store}/product',ProductController::class);
        $api->resource('/stores/{store}/category',CategoryController::class);
    });
  
});
