<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Brands;
use Illuminate\Database\Seeder;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //creating for more t12 brands 
        for($i=0;$i<3;$i++){
        Brands::factory(3)
        ->hasAttached(Store::all()->random())
        ->create();
        }
    }
}
