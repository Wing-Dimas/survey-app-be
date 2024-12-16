<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Get the answers associated with the Response
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers(){
        return $this->hasMany(ResponseAnswer::class, 'response_id');
    }

    /**
     * Filter the responses based on the given filters.
     *
     * @param Builder $query
     * @param array $filters
     * @return void
     */
    public function scopeFilter(Builder $query, array $filters) : void
    {
        $query->when($filters['search'] ?? false, function($query, $search){
            return $query->where('name', 'like', '%' . $search . '%')
                         ->orWhere('email', 'like', '%' . $search . '%')
                         ->orWhereHas('api_key', function($query) use ($search){
                             $query->where('name', 'like', '%' . $search . '%');
                         });
        });
    }
}
