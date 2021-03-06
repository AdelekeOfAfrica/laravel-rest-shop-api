<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::factory()->count(1)
            ->has(UserProfile::factory(1))
        ->create()
        ->each(
                 function($user){
                     $user->assignRole('super_admin');
                 }
        );

        User::factory()->count(2)
            ->has(UserProfile::factory(1))
        ->create()
        ->each(
                 function($user){
                     $user->assignRole('system_admin');
                 }
        );

       

        User::factory()->count(6)
            ->has(UserProfile::factory(1))
        ->create()
        ->each(
                 function($user){
                     $user->assignRole('store_admin');
                 }
        );

        User::factory()->count(10)
            ->has(UserProfile::factory(1))
        ->create()
        ->each(
                 function($user){
                     $user->assignRole('customer');
                 }
        );
    }

}
