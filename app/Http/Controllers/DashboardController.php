<?php

namespace App\Http\Controllers;

use App\Models\KwhMeter;
use App\Models\Regional;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class DashboardController extends Controller
{
    public function getAllKwhMeter()
    {
        $kwhs = KwhMeter::with([
            'hasPascabayar' => function ($pas) {
                $pas->with('biaya_admin');
            },
            'hasPrabayar' => function ($pra) {
                $pra->with('biaya_admin');
            },
            'denda'
        ])->get();

        $transaksi = $this->getTransaksi($kwhs);

        return response()->json([
            "jumlah_kwh" => count($kwhs),
            "prabayar" => [
                "nominal" => $transaksi[3],
                "jumlah" => $transaksi[2]
            ],
            "pascabayar" => [
                "nominal" => $transaksi[1],
                "jumlah" => $transaksi[0]
            ]
        ], 200);
    }

    public function getKwhByRegional()
    {
        $regionals = Regional::with(['witel' => function ($witel) {
            $witel->with(['kwh_meter' => function ($kwh) {
                $kwh->with(['hasPrabayar', 'hasPascabayar', 'pelanggan']);
            }]);
        }])->get();
        $data = [];

        foreach ($regionals as $regional) {
            $jumlah_kwh = 0;
            $count_pra = 0;
            $count_pas = 0;
            $nominal_pra = 0;
            $nominal_pas = 0;
            $pelanngan = [];

            foreach ($regional->witel as $witel) {
                $jumlah_kwh += count($witel->kwh_meter);
                $transaksi = $this->getTransaksi($witel->kwh_meter);

                $nominal_pra += $transaksi[3];
                $count_pra += $transaksi[2];
                $nominal_pas += $transaksi[1];
                $count_pas += $transaksi[0];
                foreach ($witel->kwh_meter as $kwh) {
                    array_push($pelanngan, $kwh->pelanggan->id);
                }
            }

            array_push($data, [
                "nama" => $regional->nama,
                "jumlah_kwh" => $jumlah_kwh,
                "pelanggan" => count(array_unique($pelanngan)),
                "prabayar" => [
                    "nominal" => $nominal_pra,
                    "jumlah" => $count_pra,
                ],
                "pascabayar" => [
                    "nominal" => $nominal_pas,
                    "jumlah" => $count_pas,
                ],
            ]);
        }

        return response()->json($data, 200);
    }

    public function getKwhMonthly(Request $request)
    {
        $kwhs = KwhMeter::with([
            'hasPascabayar' => function ($pas) {
                $pas->with('biaya_admin');
            },
            'hasPrabayar' => function ($pra) {
                $pra->with('biaya_admin');
            },
            'denda'
        ]);
        if ($request->month != "all")
            $kwhs->whereMonth('created_at', $request->month);

        $kwhs = $kwhs->whereYear('created_at', $request->year)->get();
        $transaksi = $this->getTransaksi($kwhs);

        $pasang_baru = KwhMeter::where('bongkar_rampung', null)->where('pasang_baru', '!=', null)->whereYear('created_at', $request->year);
        $bongkar_rampung = KwhMeter::where('bongkar_rampung', "!=", null)->whereYear('updated_at', $request->year);
        if ($request->month != "all") {
            $pasang_baru->whereMonth('created_at', $request->month);
            $bongkar_rampung->whereMonth('updated_at', $request->month);
        }

        return response()->json([
            "bongkar_rampung" => $bongkar_rampung->count(),
            "pasang_baru" => $pasang_baru->count(),
            "prabayar" => [
                "nominal" => $transaksi[3],
                "jumlah" => $transaksi[2]
            ],
            "pascabayar" => [
                "nominal" => $transaksi[1],
                "jumlah" => $transaksi[0]
            ]
        ], 200);
    }

    private function getTransaksi($kwhs)
    {
        $count_pra = 0;
        $count_pas = 0;
        $nominal_pra = 0;
        $nominal_pas = 0;

        foreach ($kwhs as $kwh) {
            $temp_denda = 0;

            foreach ($kwh->hasPascabayar as $pasca) {
                $nominal_pas += $pasca->tagihan + $pasca->biaya_admin->biaya;
            }

            foreach ($kwh->hasPrabayar as $pra) {
                $nominal_pra += $pra->tagihan + $pra->biaya_admin->biaya;
            }

            foreach ($kwh->denda as $denda) {
                $temp_denda += $denda->nominal;
            }

            if ($kwh->is_prabayar) {
                $nominal_pra += $kwh->bongkar_rampung + $kwh->pasang_baru + $temp_denda;
                $count_pra++;
            } else {
                $nominal_pas += $kwh->bongkar_rampung + $kwh->pasang_baru + $temp_denda;
                $count_pas++;
            }
        }

        return [
            $count_pas,
            $nominal_pas,
            $count_pra,
            $nominal_pra
        ];
    }
}
