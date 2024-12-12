<?php

namespace App\Models;

use App\Casts\Encryptable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{

    protected $fillable = [
        'name',
        'token',
        'active',
        'user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'token' => Encryptable::class,
        ];
    }


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

    public function scopeFilter(Builder $query, array $filters) : void
    {
        $query->when($filters['search'] ?? false, function($query, $search){
            return $query->where('name', 'like', '%' . $search . '%');
        });
    }
}
