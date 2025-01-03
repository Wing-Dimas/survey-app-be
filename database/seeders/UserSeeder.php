<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            'id' => 1,
            'username' => 'JohnDoe',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ];

        User::create($users);
    }
}
