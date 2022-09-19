<?php

namespace App\Http\Controllers;

use App\Models\Prabayar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            if ($request->keyword == null || $request->keyword == "null"){
                $query =  Prabayar::with('kwh_meters','kwh_meters.hasWitel', 'kwh_meters.hasWitel.regional', 'kwh_meters.hasTarif','kwh_meters.pelanggan', 'kwh_meters.hasPic', 'kwh_meters.hasDaya','kwh_meters.hasBiayaAdmin');
            } else {
                $query = Prabayar::search($request->keyword)->with('kwh_meters','kwh_meters.hasWitel', 'kwh_meters.hasWitel.regional', 'kwh_meters.hasTarif','kwh_meters.pelanggan', 'kwh_meters.hasPic', 'kwh_meters.hasDaya','kwh_meters.hasBiayaAdmin');
            }

            return $query->whereMonth('created_at', $request->month)->whereYear('created_at', $request->year)->orderByDesc('created_at') ->paginate(10);
            
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
        
        $this->validate($request,$validate, $messages);

        try {
            DB::transaction(
                function() use ($request) {
                    $kwh = new Prabayar();
                    $kwh->id_no_kwh_meter = $request->id_no_kwh_meter;
                    $kwh->nominal_pembelian_token = $request->nominal_pembelian_token;
                    $kwh->token = $request->token;
                    $kwh->keterangan = $request->keterangan;
                    $kwh->save();
                }
            );
            return response()->json(['message' => "Prabayar Berhasil Ditambahkan"], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prabayar  $prabayar
     * @return \Illuminate\Http\Response
     */
    public function show(Prabayar $prabayar)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prabayar  $prabayar
     * @return \Illuminate\Http\Response
     */
    public function edit(Prabayar $prabayar)
    {
        //
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
        //

        $validate = [
            'id_no_kwh_meter' => 'required',
            'nominal_pembelian_token' => 'required',
            'token' => 'required|unique:prabayars,token,'.$prabayar->id,
        ];
     
        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
        ];
        
        $this->validate($request,$validate, $messages);
        
        try {
            $prabayar->id_no_kwh_meter = $request->id_no_kwh_meter;
            $prabayar->nominal_pembelian_token = $request->nominal_pembelian_token;
            $prabayar->token = $request->token;
            $prabayar->keterangan = $request->keterangan;
            $prabayar->update();
            return response()->json(['message' => "Prabayar Berhasil Diperbaharui"], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
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
            return response()->json(['message' => $e->getMessage()],400);

        }
    }


    public function search(Request $request)
    {
        try {
            return Prabayar::search($request->keyword)->paginate(10);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
    }
}