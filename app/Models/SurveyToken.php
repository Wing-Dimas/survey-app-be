<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyToken extends Model
{
    protected $table = 'survey_tokens';

    protected $fillable = [
        'token',
        'api_key_id',
        'expires_at',
    ];

    public function api_key(){
        return $this->belongsTo(ApiKey::class, 'api_key_id');
    }
}
