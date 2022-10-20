<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'isbn' => $this->faker->ean13(),
            'title' => $this->faker->sentence(5),
            'publisher' => $this->faker->name,
            'number_of_pages' => $this->faker->randomNumber(3), // password
            'book_status_id' => 1,
        ];
    }
}
