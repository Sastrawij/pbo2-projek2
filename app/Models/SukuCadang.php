<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SukuCadang extends Model
{
    protected $table = 'suku_cadang';

    protected $guarded = [];

    public function servis(): BelongsToMany
    {
        return $this->belongsToMany(Servis::class, 'servis_suku_cadang', 'suku_cadang_id', 'servis_id')
            ->withPivot('jumlah', 'harga');
    }
}
