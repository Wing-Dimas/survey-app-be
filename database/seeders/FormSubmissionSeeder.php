<?php

namespace Database\Seeders;

use App\Models\FormSubmission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formSubmissions = [
            [
                'id' => 1,
                'field_name' => "masalah-teknis",
                'type' => "text",
                'question' => "Apakah anda mengalami masalah teknis saat menggunakan aplikasi ini?",
            ],
            [
                'id' => 2,
                'field_name' => "saran",
                'type' => "textarea",
                'question' => "Apakah anda memiliki saran atau komentar tambahan untuk meningkatkan aplikasi ini?",
            ],
            [
                'id' => 3,
                'field_name' => "rating",
                'type' => "number",
                'question' => "Seberapa puas anda dengan keseluruhan pengalaman aplikasi ini?",
            ],
        ];

        foreach ($formSubmissions as $formSubmission) {
            FormSubmission::create($formSubmission);
        }
    }
}
