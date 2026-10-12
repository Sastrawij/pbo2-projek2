<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Layanan extends Model
{
    protected $table = 'layanan';

    protected $guarded = [];

    public function servis(): BelongsToMany
    {
        return $this->belongsToMany(Servis::class, 'servis_layanan', 'layanan_id', 'servis_id')
            ->withPivot('harga');
    }
}
