<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\UserProfile;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    use Helpers;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //here is to create the 
        $fashionStore = Store::factory(1)
            ->hasAttached(
            User::factory()->count(1)
            ->has(UserProfile::factory(2))
            ->create()
            ->each(function($user){
                $user->assignRole('store_admin');
            })
        )
        ;
        $luxuryPhoneStore = Store::factory(1)
            ->hasAttached(
            User::factory()->count(2)
            ->has(UserProfile::factory(1))
            ->create()
            ->each(function($user){
                $user->assignRole('store_admin');
            })
        )
        ;
        $budgetPhoneStore = Store::factory(1)
           ->hasAttached(
            User::factory()->count(2)
            ->has(UserProfile::factory(1))
            ->create()
            ->each(function($user){
                $user->assignRole('store_admin');
            })
        )
        ;

        User::factory()->count(1)
        ->has(UserProfile::factory(1))
        ->has($fashionStore)
    ->create()
    ->each(
        function($user){
            $user->assignRole('store_admin');
        }
    );

    //this is a user having multiple stores 
    User::factory()->count(1)
    ->has(UserProfile::factory(1))
    ->has($luxuryPhoneStore)
    ->has($budgetPhoneStore)
->create()
->each(
    function($user){
        $user->assignRole('store_admin');
    }
);


    }
}
