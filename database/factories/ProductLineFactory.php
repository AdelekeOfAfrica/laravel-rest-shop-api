<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductLineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $brands =[
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
            //
            'tshirt'=>$this->faker->randomElement($brands),
            'shirt'=>$this->faker->randomElement($brands),
            'shoe'=>$this->faker->randomElement($brands),
            'pant' =>$this->faker->randomElement($brands)

        ];
    }
}
