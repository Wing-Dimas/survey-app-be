<?php

namespace Database\Seeders;

use App\Models\FormSubmission;
use App\Models\FormSubmissionOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormSubmissionOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formSubmissionOptions = [
            [
                'id' => 1,
                'form_submission_id' => 1,
            ],
            [
                'id' => 2,
                'form_submission_id' => 2,
            ],
        ];

        FormSubmissionOption::insert($formSubmissionOptions);
        FormSubmissionOption::create([
            'id' => 3,
            'form_submission_id' => 3,
            'min' => 1,
            'max' => 5,
        ]);
    }
}
