<?php

namespace Database\Seeders;

use App\Models\ResponseAnswer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResponseAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $responseAnswer = [
            [
                'id' => 1,
                'answer' => 'Ada, ketika saya ingin login',
                'response_id' => 1,
                'form_submission_id' => 1,
            ],
            [
                'id' => 2,
                'answer' => 'Tambahkan fitur remember me',
                'response_id' => 1,
                'form_submission_id' => 2,
            ],
            [
                'id' => 3,
                'answer' => '5',
                'response_id' => 1,
                'form_submission_id' => 3,
            ],
            [
                'id' => 4,
                'answer' => 'Ada ketika saya ingin melakukan pembayaran',
                'response_id' => 2,
                'form_submission_id' => 1,
            ],
            [
                'id' => 5,
                'answer' => 'Tidak ada',
                'response_id' => 2,
                'form_submission_id' => 2,
            ],
            [
                'id' => 6,
                'answer' => '3',
                'response_id' => 2,
                'form_submission_id' => 3,
            ],
        ];

        ResponseAnswer::insert($responseAnswer);

    }
}
