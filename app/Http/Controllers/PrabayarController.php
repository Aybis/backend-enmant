<?php

namespace App\Http\Controllers;

use App\Models\Prabayar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportPrabayar;
use App\Exports\ExportPrabayar;

class PrabayarController extends Controller
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
                $query =  Prabayar::with('kwh_meters', 'witel', 'witel.regional', 'kwh_meters.hasTarif', 'pelanggan', 'pic', 'kwh_meters.hasDaya', 'biaya_admin');
            } else if ($request->meteran != null) {
                $query =  Prabayar::with('kwh_meters', 'witel', 'witel.regional', 'kwh_meters.hasTarif', 'pelanggan', 'pic', 'kwh_meters.hasDaya', 'biaya_admin')->where('id_no_kwh_meter', $request->meteran);
            } else {
                $query = Prabayar::search($request->keyword)->with('kwh_meters', 'witel', 'witel.regional', 'kwh_meters.hasTarif', 'pelanggan', 'pic', 'kwh_meters.hasDaya', 'biaya_admin');
            }

            return $query->whereMonth('created_at', $request->month)->whereYear('created_at', $request->year)->orderByDesc('created_at')->paginate(10);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePrabayarRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = [
            'id_no_kwh_meter' => 'required',
            'nominal_pembelian_token' => 'required',
            'token' => 'required|unique:prabayars',
            'keterangan' => 'required',


        ];

        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
        ];

        $this->validate($request, $validate, $messages);

        try {
            DB::transaction(
                function () use ($request) {
                    $kwh = new Prabayar();
                    $kwh->id_no_kwh_meter = $request->id_no_kwh_meter;
                    $kwh->nominal_pembelian_token = $request->nominal_pembelian_token;
                    $kwh->token = $request->token;
                    $kwh->keterangan = $request->keterangan;
                    $kwh->id_biaya_admin = $request->id_biaya_admin;
                    $kwh->id_pic = $request->id_pic;
                    $kwh->save();
                }
            );
            return response()->json(['message' => "Prabayar Berhasil Ditambahkan"], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePrabayarRequest  $request
     * @param  \App\Models\Prabayar  $prabayar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prabayar $prabayar)
    {
        $validate = [
            'id_no_kwh_meter' => 'required',
            'nominal_pembelian_token' => 'required',
            'token' => 'required|unique:prabayars,token,' . $prabayar->id,
        ];

        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
        ];

        $this->validate($request, $validate, $messages);

        try {
            $prabayar->id_no_kwh_meter = $request->id_no_kwh_meter;
            $prabayar->nominal_pembelian_token = $request->nominal_pembelian_token;
            $prabayar->token = $request->token;
            $prabayar->keterangan = $request->keterangan;
            $prabayar->id_biaya_admin = $request->id_biaya_admin;
            $prabayar->id_pic = $request->id_pic;
            $prabayar->update();
            return response()->json(['message' => "Prabayar Berhasil Diperbaharui"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prabayar  $prabayar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prabayar $prabayar)
    {
        try {
            $prabayar = Prabayar::findOrFail($prabayar->id);
            $prabayar->delete();
            return response()->json(['message' => "Prabayar Berhasil Dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function search(Request $request)
    {
        try {
            return Prabayar::search($request->keyword)->paginate(10);
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
            Excel::import(new ImportPrabayar, $file, \Maatwebsite\Excel\Excel::XLSX);
            return response()->json(['message' => "Success Upload data prabayar"], 200);
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
        return Excel::download(new ExportPrabayar($request, $year, $month),  $year . '-' . $month . '.xlsx');
    }
}