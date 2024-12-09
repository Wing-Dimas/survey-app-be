<?php

namespace Database\Seeders;

use App\Models\ApiKey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
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
                'token' => Crypt::encryptString(Uuid::uuid4()),
                'is_active' => true,
                'user_id' => 2,
            ],
            [
                'id' => 2,
                'name' => 'Aplikasi Forum',
                'token' => Crypt::encryptString(Uuid::uuid4()),
                'is_active' => false,
                'user_id' => 2,
            ],
        ];

        ApiKey::insert($apiKeys);

    }
}
