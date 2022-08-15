<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prabayar extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function hasKwhMeter()
    {
        return $this->belongsTo(KwhMeter::class, 'id');
    }
    

}