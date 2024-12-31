<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $fillable = [
        'field_name',
        'type',
        'question',
        'required',
        'rule'
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

    public function scopeFilter(Builder $query, array $filters) : void
    {
        $query->when($filters['search'] ?? false, function($query, $search){
            return $query->where('field_name', 'like', '%' . $search . '%')
                         ->where('question', 'like', '%' . $search . '%');
        });

        $query->when($filters['type'] ?? false, function($query, $type){
            return $query->where('type', $type);
        });
    }
}
