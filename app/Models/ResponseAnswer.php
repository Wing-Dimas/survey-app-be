<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResponseAnswer extends Model
{
    protected $fillable = [
        'answer',
        'response_id',
        'form_submission_id',
    ];

    /**
     * The response that this answer is associated with.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function response(){
        return $this->belongsTo(Response::class, 'response_id');
    }

    /**
     * The form submission that this response answer is associated with.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form_submission(){
        return $this->belongsTo(FormSubmission::class, 'form_submission_id');
    }
}
