<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pascabayar extends Model
{
    use HasFactory;

    public function hasKwhMeter()
    {
        return $this->belongsTo(KwhMeter::class, 'id');
    }
}