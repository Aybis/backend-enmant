<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Denda extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'dendas';

    public function kwh_meters()
    {
        return $this->belongsTo(KwhMeter::class, 'id_no_kwh_meter', 'id');
    }
}
