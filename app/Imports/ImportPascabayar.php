<?php

namespace App\Imports;

use App\Models\Pascabayar;
use App\Models\KwhMeter;
use App\Models\PIC;
use App\Models\BiayaAdmin;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportPascabayar implements ToModel, WithStartRow
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 3;
    }

    /**
     * Model 
     */
    public function model(array $row)
    {

        $id = $this->getKwhMeter($row[1]);
        $pic = $this->getPic($row[12]);
        $biaya = $this->getBiaya($row[11]);

        if ($id != null) {
            return new Pascabayar([
                'id_no_kwh_meter' => $id,
                'id_pic' => $pic,
                'id_biaya_admin' => $biaya,
                'meter_awal' => $row[7],
                'meter_akhir' => $row[8],
                'selisih' => $row[9],
                'tagihan' => $row[10]
            ]);
        }
    }

    private function getKwhMeter($no_pelanggan)
    {
        $temp = KwhMeter::where('no_pelanggan', $no_pelanggan)->first();
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
