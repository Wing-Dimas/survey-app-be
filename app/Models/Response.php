<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = [
        'name',
        'email',
        'api_key_id',
    ];

    /**
     * The ApiKey instance that this Response belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function api_key(){
        return $this->belongsTo(ApiKey::class, 'api_key_id');
    }
}
