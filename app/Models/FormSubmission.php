<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $fillable = [
        'field_name',
        'type',
        'question',
    ];

    /**
     * Get the Options associated with the FormSubmission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options(){
        return $this->hasMany(FormSubmissionOption::class, 'form_submission_id');
    }

    /**
     * Get the response answers associated with the FormSubmission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers(){
        return $this->hasMany(ResponseAnswer::class, 'form_submission_id');
    }
}
