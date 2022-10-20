<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookStatus;

class BookStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BookStatus::create(
            [
                'id' => 1,
                'name' => 'Available',
            ]
        );

        BookStatus::create(
            [
                'id' => 4,
                'name' => 'Not Available',
            ]
        );

    }
}
