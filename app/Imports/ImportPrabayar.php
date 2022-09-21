<?php

namespace App\Imports;

use App\Models\Prabayar;
use App\Models\KwhMeter;
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
        $id = $this->getKwhMeter($row[2]);
        if ($id != null) {
            return new Prabayar([
                'id_no_kwh_meter' => $id,
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
}
