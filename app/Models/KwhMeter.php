<?php

namespace App\Models;

use App\Support\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KwhMeter extends Model
{
    use HasFactory;
    use Searchable;

    protected $primaryKey = 'id';
    protected $table = 'kwh_meters';

    protected $searchable = [
        'no_pelanggan',
        'no_kwh_meter',
        'pelanggan.nama_pelanggan',
        'hasTarif.tarif',
        'hasDaya.daya',
        'hasBiayaAdmin.biaya',
        'hasPic.nama_pic',
        'hasWitel.nama'
    ];


    public function hasWitel()
    {
        return $this->belongsTo(Witel::class, 'id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_tbl_pelanggan', 'id');
    }

    public function hasPic()
    {
        return $this->belongsTo(PIC::class, 'id_pic', 'id');
    }

    public function hasTarif()
    {
        return $this->belongsTo(Tarif::class, 'id_tarif', 'id');
    }

    public function hasDaya()
    {
        return $this->belongsTo(Daya::class, 'id_daya', 'id');
    }

    public function hasBiayaAdmin()
    {
        return $this->belongsTo(BiayaAdmin::class, 'id_biaya_admin', 'id');
    }

    public function hasPrabayar()
    {
        return $this->hasMany(Prabayar::class, 'id_no_kwh_meter', 'id');
    }

    public function hasPascabayar()
    {
        return $this->hasMany(Pascabayar::class, 'id_no_kwh_meter', 'id');
    }
}
