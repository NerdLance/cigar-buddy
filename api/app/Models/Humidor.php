<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Humidor extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'humidity',
    ];

    protected $appends = [
        'total_value',
        'humidor_cigars_count'
    ];

    public function humidor_cigars(): HasMany
    {
        return $this->hasMany(HumidorCigar::class);
    }

    public function getTotalValueAttribute()
    {
        return $this->humidor_cigars->sum(fn($cigar) => $cigar->price_per_cigar * $cigar->quantity);
    }

    public function getHumidorCigarsCountAttribute()
    {
        return $this->humidor_cigars->sum(fn($cigar) => $cigar->quantity);
    }
}
