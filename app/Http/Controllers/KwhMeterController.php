<?php

namespace App\Http\Controllers;


use App\Models\KwhMeter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KwhMeterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->search == 'dropdown') {
                return KwhMeter::with('hasWitel', 'hasWitel.regional', 'hasTarif', 'pelanggan', 'hasDaya')->get();
            } else if ($request->search === 'prabayar') {
                return KwhMeter::with('hasWitel', 'hasWitel.regional', 'hasTarif', 'pelanggan', 'hasDaya')->where('is_prabayar', 1)->get();
            } else if ($request->search === 'pascabayar') {
                return KwhMeter::with('hasWitel', 'hasWitel.regional', 'hasTarif', 'pelanggan', 'hasDaya')->where('is_prabayar', 0)->get();
            } else if ($request->keyword == null || $request->keyword == '') {
                return KwhMeter::with('hasWitel', 'hasWitel.regional', 'hasTarif', 'pelanggan', 'hasDaya')->paginate(10);
            } else {
                return KwhMeter::search($request->keyword)->with('hasWitel', 'hasWitel.regional', 'hasTarif', 'pelanggan', 'hasDaya')->paginate(10);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }


        // $model = KwhMeter::with('hasWitel', 'hasWitel.regional', 'hasTarif','pelanggan', 'hasPic', 'hasDaya','hasBiayaAdmin')->paginate(10);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreKwhMeterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = [
            'no_pelanggan' => 'required|unique:kwh_meters',
            'id_tbl_pelanggan' => 'required',
        ];

        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
            'id_tbl_pelanggan.required' => 'Pelanggan tidak boleh kosong',
        ];

        $this->validate($request, $validate, $messages);

        try {
            DB::transaction(
                function () use ($request) {
                    $kwh = new KwhMeter();
                    $kwh->id_tbl_pelanggan = $request->id_tbl_pelanggan;
                    $kwh->no_pelanggan = $request->no_pelanggan;
                    $kwh->no_kwh_meter = $request->no_kwh_meter;
                    $kwh->id_tarif = $request->id_tarif;
                    $kwh->id_witel = $request->id_witel;
                    $kwh->id_daya = $request->id_daya;
                    $kwh->is_prabayar = $request->is_prabayar;
                    $kwh->bongkar_rampung = $request->bongkar_rampung;
                    $kwh->pasang_baru = $request->pasang_baru;
                    $kwh->is_active = 1;
                    $kwh->save();
                }
            );
            return response()->json(['message' => "ID Pelanggan/No. KWH Meter Berhasil Ditambahkan"], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KwhMeter  $kwhMeter
     * @return \Illuminate\Http\Response
     */
    public function show(KwhMeter $meteran)
    {
        $result = $meteran::with((['hasWitel', 'hasWitel.regional', 'hasTarif', 'pelanggan', 'hasPic', 'hasDaya', 'hasBiayaAdmin']))->find($meteran->id);
        return response()->json($result, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KwhMeter  $kwhMeter
     * @return \Illuminate\Http\Response
     */
    public function edit(KwhMeter $kwhMeter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateKwhMeterRequest  $request
     * @param  \App\Models\KwhMeter  $kwhMeter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KwhMeter $meteran)
    {

        $validate = [
            'no_pelanggan' => 'required|unique:kwh_meters,no_pelanggan,' . $meteran->id,
            // 'no_kwh_meter' => 'required|unique:kwh_meters,no_kwh_meter,' . $meteran->id,
        ];

        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
            'id_tbl_pelanggan.required' => 'Pelanggan tidak boleh kosong',
        ];

        $this->validate($request, $validate, $messages);

        try {
            $meteran->id_tbl_pelanggan = $request->id_tbl_pelanggan;
            $meteran->no_pelanggan = $request->no_pelanggan;
            $meteran->no_kwh_meter = $request->no_kwh_meter;
            $meteran->id_tarif = $request->id_tarif;
            $meteran->id_witel = $request->id_witel;
            $meteran->id_daya = $request->id_daya;
            $meteran->is_prabayar = $request->is_prabayar;
            $meteran->bongkar_rampung = $request->bongkar_rampung;
            $meteran->pasang_baru = $request->pasang_baru;

            $meteran->update();
            return response()->json(['message' => "ID Pelanggan/No. KWH Meter Berhasil Diperbaharui"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KwhMeter  $kwhMeter
     * @return \Illuminate\Http\Response
     */
    public function destroy(KwhMeter $meteran)
    {
        try {
            $meteran = KwhMeter::findOrFail($meteran->id);
            $meteran->delete();
            return response()->json(['message' => "KWH Meteran Berhasil Dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    /**
     * Search 
     */
    public function search(Request $request)
    {
        try {
            return KwhMeter::search($request->keyword)->paginate(10);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}