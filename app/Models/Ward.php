<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ward extends Model
{
    protected $fillable = [
        'ward_name',
        'ward_type',
        'floor',
        'capacity',
        'status',
    ];

    /**
     * Get the beds for the ward.
     */
    public function beds(): HasMany
    {
        return $this->hasMany(Bed::class);
    }
}
