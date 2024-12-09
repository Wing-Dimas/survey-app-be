<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $fillable = [
        'name',
        'token',
        'is_active',
        'user_id',
    ];

    /**
     * Get the user that owns the ApiKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the responses for the ApiKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function responses(){
        return $this->hasMany(Response::class, 'api_key_id');
    }
}
