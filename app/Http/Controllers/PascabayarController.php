<?php

namespace App\Http\Controllers;

use App\Models\Pascabayar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            if ($request->keyword == null || $request->keyword == "null") 
            {
                return Pascabayar::with('kwh_meters','kwh_meters.hasWitel', 'kwh_meters.hasWitel.regional', 'kwh_meters.hasTarif','kwh_meters.pelanggan', 'kwh_meters.hasPic', 'kwh_meters.hasDaya','kwh_meters.hasBiayaAdmin')->paginate(10);
            } else {
        
                return Pascabayar::search($request->keyword)->with('kwh_meters','kwh_meters.hasWitel', 'kwh_meters.hasWitel.regional', 'kwh_meters.hasTarif','kwh_meters.pelanggan', 'kwh_meters.hasPic', 'kwh_meters.hasDaya','kwh_meters.hasBiayaAdmin')->paginate(10);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
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
        
        $this->validate($request,$validate, $messages);

        try {
            DB::transaction(
                function() use ($request) {
                    $pascabayar = new Pascabayar();
                    $pascabayar->id_no_kwh_meter = $request->id_no_kwh_meter;
                    $pascabayar->meter_awal = $request->meter_awal;
                    $pascabayar->meter_akhir = $request->meter_akhir;
                    $pascabayar->selisih = $request->selisih;
                    $pascabayar->tagihan = $request->tagihan;
                    $pascabayar->save();
                }
            );
            return response()->json(['message' => "Pascabayar Berhasil Ditambahkan"], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pascabayar  $pascabayar
     * @return \Illuminate\Http\Response
     */
    public function show(Pascabayar $pascabayar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pascabayar  $pascabayar
     * @return \Illuminate\Http\Response
     */
    public function edit(Pascabayar $pascabayar)
    {
        //
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
        
        $this->validate($request,$validate, $messages);

        try {
            $pascabayar->id_no_kwh_meter = $request->id_no_kwh_meter;
                    $pascabayar->meter_awal = $request->meter_awal;
                    $pascabayar->meter_akhir = $request->meter_akhir;
                    $pascabayar->selisih = $request->selisih;
                    $pascabayar->tagihan = $request->tagihan;
                    $pascabayar->update();
            return response()->json(['message' => "Pascabayar Berhasil Diperbaharui"], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
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
            return response()->json(['message' => $e->getMessage()],400);
        }
    }

    public function search(Request $request)
    {
        try {
            return Pascabayar::search($request->keyword)->paginate(10);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
    }
}