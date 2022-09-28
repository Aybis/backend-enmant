<?php

namespace App\Exports;

use App\Models\Pascabayar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportPascabayar implements FromCollection, WithHeadings, WithMapping
{
    protected $month = null;
    protected $year = null;

    public function __construct($request, $year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $prabayar = Pascabayar::with(['kwh_meters' => function ($kwh) {
            $kwh->with(['hasTarif', 'hasDaya', 'pelanggan']);
        }, 'witel.regional', 'pic', 'biaya_admin'])->whereYear('created_at', $this->year);

        if ($this->month != 13)
            $prabayar->whereMonth('created_at', $this->month);

        return $prabayar->get();
    }

    public function headings(): array
    {
        return [
            'ID PEL',
            'NAMA PEL',
            'TARIF',
            'DAYA',
            'WITEL',
            'AREA',
            'METER AWAL',
            'METER AKHIR',
            'SELISIH METER',
            'TAGIHAN',
            'ADMIN',
            'PIC',
        ];
    }

    public function map($row): array
    {
        return [
            (string)$row->kwh_meters->no_pelanggan . " ",
            $row->pelanggan->nama_pelanggan,
            $row->kwh_meters->hasTarif->tarif,
            number_format($row->kwh_meters->hasDaya->daya),
            $row->witel->alias,
            $row->witel->regional->nama,
            $row->meter_awal,
            $row->meter_akhir,
            $row->selisih,
            $row->tagihan,
            number_format($row->biaya_admin->biaya),
            $row->pic->nama_pic,
        ];
    }
}
