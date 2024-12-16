<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmissionOption extends Model
{
    protected $fillable = [
        'form_submission_id',
        'key',
        'value',
        'min',
        'max',
    ];

    /**
     * The field that this validation rule applies to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form_submission(){
        return $this->belongsTo(FormSubmission::class, 'form_submission_id');
    }
}
