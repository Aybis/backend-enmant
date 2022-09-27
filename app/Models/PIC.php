<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PIC extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pics';
    protected $guarded = [];

    public function prabayars()
    {
        return $this->hasMany(Prabayar::class, 'id_pic', 'id');
    }

    public function pascabayars()
    {
        return $this->hasMany(Pascabayar::class, 'id_pic', 'id');
    }
}