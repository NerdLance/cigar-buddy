<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralImage extends Model
{
    protected $fillable = [
        'user_id',
        's3_path',
        's3_url',
        'filename',
    ];
}
