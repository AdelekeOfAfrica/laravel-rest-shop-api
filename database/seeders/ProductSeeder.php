<?php

namespace Database\Seeders;

use App\Models\product;
use App\Models\productLine;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()

    {
        //
        for($i=0; $i<=5; $i++){

        //product::factory()->count(5)
        //->hasAttached(productLine::factory())
        //->create();
        //productLine::factory()->hasAttached(
            //product::factory()->count(5)
        //)->create();
        product::factory()->count(5)->create();
        }

        

        

    }

}
