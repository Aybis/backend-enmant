<?php

namespace App\Exports;

use App\Models\Prabayar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportPrabayar implements FromCollection, WithHeadings, WithMapping
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

        $prabayar = Prabayar::with(['kwh_meters' => function ($kwh) {
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
            'NO METER',
            'NAMA PEL',
            'WITEL',
            'AREA',
            'TARIF',
            'DAYA',
            'PIC PEMBAYARAN',
            'NOMINAL TOKEN PEMBAYARAN',
            'BIAYA ADMIN',
            'NO TOKEN',
            'NAMA FILE',
            'KETERANGAN',
        ];
    }

    public function map($row): array
    {
        return [
            (string)$row->kwh_meters->no_pelanggan,
            $row->kwh_meters->no_kwh_meter,
            $row->pelanggan->nama_pelanggan,
            $row->witel->alias,
            $row->witel->regional->nama,
            $row->kwh_meters->hasTarif->tarif,
            number_format($row->kwh_meters->hasDaya->daya),
            $row->pic->nama_pic,
            number_format($row->nominal_pembelian_token),
            number_format($row->Biaya_admin->biaya),
            $row->token,
            "",
            $row->keterangan,
        ];
    }
}
