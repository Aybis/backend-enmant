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
    protected $primaryKey = 'id';
    protected $guarded = [];


    public function kwhMeter()
    {
        return $this->hasMany(KwhMeter::class, 'id_tbl_pelanggan', 'id');
    }

    public function prabayars()
    {
        return $this->hasManyThrough(Prabayar::class, KwhMeter::class, 'id_tbl_pelanggan', 'id_no_kwh_meter', 'id');
    }

    public function pascabayars()
    {
        return $this->hasManyThrough(Pascabayar::class, KwhMeter::class, 'id_tbl_pelanggan', 'id_no_kwh_meter', 'id');
    }
}