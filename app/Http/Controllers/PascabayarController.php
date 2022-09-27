<?php

namespace App\Http\Controllers;

use App\Exports\ExportPascabayar;
use App\Models\Pascabayar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportPrabayar;
use App\Exports\ExportPrabayar;
use App\Imports\ImportPascabayar;

class PascabayarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            if ($request->keyword == null || $request->keyword == "null") {
                $query = Pascabayar::with('kwh_meters', 'pelanggan', 'witel.regional', 'kwh_meters.hasTarif', 'kwh_meters.hasDaya', 'biaya_admin', 'pic');
            } else {
                $query = Pascabayar::search($request->keyword)->with('kwh_meters', 'pelanggan', 'witel.regional', 'kwh_meters.hasTarif', 'kwh_meters.hasDaya', 'biaya_admin', 'pic');
            }

            return $query->whereMonth('created_at', $request->month)->whereYear('created_at', $request->year)->orderByDesc('created_at')->paginate(10);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePascabayarRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = [
            'id_no_kwh_meter' => 'required',
            'meter_awal' => 'required',
            'meter_akhir' => 'required',
            'selisih' => 'required',
            'tagihan' => 'required',
        ];

        $messages = [
            'required' => ':Attribute tidak boleh kosong',
        ];

        $this->validate($request, $validate, $messages);

        try {
            DB::transaction(
                function () use ($request) {
                    $pascabayar = new Pascabayar();
                    $pascabayar->id_no_kwh_meter = $request->id_no_kwh_meter;
                    $pascabayar->meter_awal = $request->meter_awal;
                    $pascabayar->meter_akhir = $request->meter_akhir;
                    $pascabayar->selisih = $request->selisih;
                    $pascabayar->tagihan = $request->tagihan;
                    $pascabayar->id_biaya_admin = $request->id_biaya_admin;
                    $pascabayar->id_pic = $request->id_pic;
                    $pascabayar->denda = $request->denda;
                    $pascabayar->reference_denda = $request->reference_denda;
                    $pascabayar->status = $request->status;
                    $pascabayar->tanggal_transaksi = $request->tanggal_transaksi;

                    $pascabayar->save();
                }
            );
            return response()->json(['message' => "Layanan Pascabayar Berhasil Ditambahkan"], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePascabayarRequest  $request
     * @param  \App\Models\Pascabayar  $pascabayar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pascabayar $pascabayar)
    {
        $validate = [
            'id_no_kwh_meter' => 'required',
            'meter_awal' => 'required',
            'meter_akhir' => 'required',
            'selisih' => 'required',
            'tagihan' => 'required',
        ];

        $messages = [
            'required' => ':Attribute tidak boleh kosong',
        ];

        $this->validate($request, $validate, $messages);

        try {
            $pascabayar->id_no_kwh_meter = $request->id_no_kwh_meter;
            $pascabayar->meter_awal = $request->meter_awal;
            $pascabayar->meter_akhir = $request->meter_akhir;
            $pascabayar->selisih = $request->selisih;
            $pascabayar->tagihan = $request->tagihan;
            $pascabayar->id_biaya_admin = $request->id_biaya_admin;
            $pascabayar->id_pic = $request->id_pic;
            $pascabayar->denda = $request->denda;
            $pascabayar->reference_denda = $request->reference_denda;
            $pascabayar->status = $request->status;
            $pascabayar->tanggal_transaksi = $request->tanggal_transaksi;

            $pascabayar->update();
            return response()->json(['message' => "Layanan Pascabayar Berhasil Diperbaharui"], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pascabayar  $pascabayar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pascabayar $pascabayar)
    {
        try {
            $pascabayar = Pascabayar::find($pascabayar->id);
            $pascabayar->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function search(Request $request)
    {
        try {
            return Pascabayar::search($request->keyword)->paginate(10);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    /**
     * Import data excel 
     *
     */
    public function import(Request $request)
    {
        try {
            $file = $request->file('file');
            Excel::import(new ImportPascabayar, $file, \Maatwebsite\Excel\Excel::XLSX);
            return response()->json(['message' => "Success Upload data pascabayar"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    /**
     * Export data excel 
     *
     */
    public function export(Request $request)
    {
        $month = $request->month == null ? Carbon::now()->month : $request->month;
        $year = $request->year == null ? Carbon::now()->year : $request->year;
        return Excel::download(new ExportPascabayar($request, $year, $month),  $year . '-' . $month . '.xlsx');
    }
}