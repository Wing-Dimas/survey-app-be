<?php

namespace Database\Seeders;

use App\Models\ApiKey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class ApiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apiKeys = [
            [
                'id' => 1,
                'name' => 'Aplikasi Todo',
                'token' => Uuid::uuid4(),
                'active' => true,
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Aplikasi Forum',
                'token' => Uuid::uuid4(),
                'active' => false,
                'user_id' => 1,
            ],
        ];

        foreach ($apiKeys as $apiKey) {
            ApiKey::create($apiKey);
        }
    }
}
