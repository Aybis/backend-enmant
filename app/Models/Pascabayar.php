<?php

namespace App\Models;

use App\Support\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pascabayar extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    
    protected $searchable = [
        'id_no_kwh_meter',
        'meter_awal',
        'meter_akhir',
        'tagihan',
        'selisih',
        'kwh_meters.no_pelanggan',
        'kwh_meters.no_kwh_meter',
        'pelanggan.nama_pelanggan',
        'witel.nama', 
        'pic.nama_pic', 
        'daya.daya', 
        'tarif.tarif', 
    ];
    

    public function kwh_meters()
    {
        return $this->belongsTo(KwhMeter::class, 'id_no_kwh_meter','id');
    }

    public function witel()
    {
        return $this->hasOneThrough((Witel::class), (KwhMeter::class), 'id', 'id', 'id_no_kwh_meter', 'id');
    }

    public function pelanggan()
    {
        return $this->hasOneThrough((Pelanggan::class), (KwhMeter::class), 'id', 'id', 'id_no_kwh_meter', 'id_tbl_pelanggan');
    }
    public function tarif()
    {
        return $this->hasOneThrough((Tarif::class), (KwhMeter::class), 'id', 'id', 'id_no_kwh_meter', 'id_tarif');
    }
    public function pic()
    {
        return $this->hasOneThrough((PIC::class), (KwhMeter::class), 'id', 'id', 'id_no_kwh_meter', 'id_pic');
    }

    public function biaya_admin()
    {
        return $this->hasOneThrough((BiayaAdmin::class), (KwhMeter::class), 'id', 'id', 'id_no_kwh_meter', 'id_biaya_admin');
    }
    
    public function daya()
    {
        return $this->hasOneThrough((Daya::class), (KwhMeter::class), 'id', 'id', 'id_no_kwh_meter', 'id_daya');
    }
}