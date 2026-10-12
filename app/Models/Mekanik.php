<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mekanik extends Model
{
    protected $table = 'mekanik';

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function servis(): HasMany
    {
        return $this->hasMany(Servis::class, 'mekanik_id');
    }
}
