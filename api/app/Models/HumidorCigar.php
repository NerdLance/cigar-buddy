<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HumidorCigar extends Model
{
    protected $fillable = [
        'user_id',
        'humidor_id',
        'name',
        'ring',
        'length',
        'quantity',
        'price_per_cigar',
    ];

    public function humidor(): BelongsTo
    {
        return $this->belongsTo(Humidor::class);
    }
}
