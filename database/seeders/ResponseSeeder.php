<?php

namespace Database\Seeders;

use App\Models\Response;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $responses = [
            [
                'id' => 1,
                'name' => 'silver wing',
                'email' => 'silverwing@gmail.com',
                'api_key_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'bright bill',
                'email' => 'bill@gmail.com',
                'api_key_id' => 1,
            ],
        ];

        Response::insert($responses);
    }
}
