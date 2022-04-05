<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Brands;
use App\Models\productLine;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class ProductLineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //for($i=0; $i<=3; $i++){
            //productLine::factory(3)
            //->hasAttached(Store::all()->random(),Brands::all()->random(),User::all()->random())
            //->create();

       // }//

       //$ProductLine = ProductLine::factory(1)
         //   ->hasAttached(
           // User::factory()->count(1)
            //->has(UserProfile::factory(1))
            //->create()
            //->each(function($user){
              //  $user->assignRole('store_admin');
            //})
        //);


       // User::factory()->count(1)
        //->has(UserProfile::factory(1))
        //->has($ProductLine)
    //->create()
    //->each(
       // function($user){
           // $user->assignRole('store_admin');
        //}
    //);


    //for ($i=0; $i<=3; $i++){
       //productLine::Factory(3)

       //->hasAttached(Brands::all()->random())
        //->create();

      //}

      for($i=0; $i<3; $i++){
       // productLine::factory()->hasAttached(Brands::factory()->count(3))->create(); //wrong
        Brands::factory()->hasAttached(productLine::factory()->count(3)->create());
      }

 
}
}