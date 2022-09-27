<?php

namespace App\Models;

use App\Support\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KwhMeter extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'kwh_meters';

    protected $searchable = [
        'no_pelanggan',
        'no_kwh_meter',
        'pelanggan.nama_pelanggan',
        'hasTarif.tarif',
        'hasDaya.daya',
        'hasWitel.nama'
    ];


    public function hasWitel()
    {
        return $this->belongsTo(Witel::class, 'id_witel', 'id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_tbl_pelanggan', 'id');
    }

    public function hasTarif()
    {
        return $this->belongsTo(Tarif::class, 'id_tarif', 'id');
    }

    public function hasDaya()
    {
        return $this->belongsTo(Daya::class, 'id_daya', 'id');
    }

    public function hasPrabayar()
    {
        return $this->hasMany(Prabayar::class, 'id_no_kwh_meter', 'id');
    }

    public function hasPascabayar()
    {
        return $this->hasMany(Pascabayar::class, 'id_no_kwh_meter', 'id');
    }

    public function denda()
    {
        return $this->hasMany(Denda::class, 'id_no_kwh_meter', 'id');
    }
}