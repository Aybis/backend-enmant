<?php

namespace App\Imports;

use App\Models\Prabayar;
use App\Models\KwhMeter;
use App\Models\PIC;
use App\Models\BiayaAdmin;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportPrabayar implements ToModel, WithStartRow
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Model 
     */
    public function model(array $row)
    {
        $kwh = $this->getKwhMeter($row[2]);
        $pic = $this->getPic($row[8]);
        $biaya = $this->getBiaya($row[10]);
        if ($kwh != null) {
            return new Prabayar([
                'id_no_kwh_meter' => $kwh,
                'id_pic' => $pic,
                'id_biaya_admin' => $biaya,
                'nominal_pembelian_token' => $row[9],
                'token' => $row[11],
                'keterangan' => $row[13]
            ]);
        }
    }

    private function getKwhMeter($no_meter)
    {
        $temp = KwhMeter::where('no_kwh_meter', $no_meter)->first();
        return $temp ? $temp->id : null;
    }

    private function getPic($pic)
    {
        $temp = PIC::where('nama_pic', $pic)->first();
        return $temp ? $temp->id : null;
    }

    private function getBiaya($biaya)
    {
        $temp = BiayaAdmin::where('biaya', $biaya)->first();
        return $temp ? $temp->id : null;
    }
}
