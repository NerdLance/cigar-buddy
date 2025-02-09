<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cigar extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'ring',
        'length',
        'notes',
        'summary',
        'rating',
    ];
}
