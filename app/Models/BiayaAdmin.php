<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BiayaAdmin extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function prabayars()
    {
        return $this->hasMany(Prabayar::class, 'id_biaya_admin', 'id');
    }

    public function pascabayars()
    {
        return $this->hasMany(Pascabayar::class, 'id_biaya_admin', 'id');
    }
}