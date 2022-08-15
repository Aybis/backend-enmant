<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Witel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'witels';
    protected $guarded = [];

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'id_regional', 'id');
    }

    public function kwh_meter()
    {
        return $this->hasMany(KwhMeter::class, 'id_witel');
    }
    

}