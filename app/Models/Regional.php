<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regional extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $guarded = [];


    public function witel()
    {
        return $this->hasMany(Witel::class, 'id_regional');
    }

}