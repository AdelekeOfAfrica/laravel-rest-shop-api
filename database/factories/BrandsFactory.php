<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BrandsFactory extends Factory
{
   
    /**
     * Define the model's default state.
     *
     * @return array
     */


    
    public function definition()
    {
      //
        $brands=[
            'Louis Vuitton',  
            'Gucci',
            'Burberry',
            'chanel',
            'prada',
            'versace',
            'Armani',
            'puma',
            'Adidas',
            'H&M',
            'Rolex',
            'Nike',
            'Allen Solley',
            'Van Heusen',
            'Apple',
            'Samsung',
            'One Plus',
            'Pope',
            'Us Polo',
            'United Colors Of Benetton'    
        ];

        return [
            'name'=>$this->faker->unique()->randomElement($brands),
            'details'=>$this->faker->paragraph()
        ];
    }
}
