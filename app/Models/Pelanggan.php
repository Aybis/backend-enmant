<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pelanggans';
    protected $guarded = [];


    public function kwhMeter()
    {
        return $this->hasMany(KwhMeter::class, 'id_tbl_pelanggan', 'id');
    }
}