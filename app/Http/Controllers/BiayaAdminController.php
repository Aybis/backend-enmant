<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBiayaAdminRequest;
use App\Http\Requests\UpdateBiayaAdminRequest;
use App\Models\BiayaAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BiayaAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->search !== null) {

            $result = BiayaAdmin::get();
        } else {
            $result = BiayaAdmin::where('biaya', 'like', '%' . $request->keyword . '%')->paginate(10);
        }
        return response()->json($result, 200);
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
     * @param  \App\Http\Requests\StoreBiayaAdminRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::transaction(
                function () use ($request) {
                    $pelanggan = new BiayaAdmin();
                    $pelanggan->biaya = $request->biaya;
                    $pelanggan->is_active = 1;
                    $pelanggan->save();
                }
            );
            return response()->json(['message' => "Biaya Admin Berhasil Ditambahkan"], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BiayaAdmin  $biayaAdmin
     * @return \Illuminate\Http\Response
     */
    public function show(BiayaAdmin $biayaAdmin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BiayaAdmin  $biayaAdmin
     * @return \Illuminate\Http\Response
     */
    public function edit(BiayaAdmin $biayaAdmin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBiayaAdminRequest  $request
     * @param  \App\Models\BiayaAdmin  $biayaAdmin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BiayaAdmin $biayaAdmin)
    {

        $id = $biayaAdmin == null ? '' : $biayaAdmin->id;
        $validate = [
            'biaya' => 'required|unique:biaya_admins,biaya,' . $biayaAdmin->id,
        ];

        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
        ];

        $this->validate($request, $validate, $messages);

        try {
            $biayaAdmin->biaya = $request->biaya;
            $biayaAdmin->update();
            return response()->json(['message' => "Biaya Admin Berhasil Diperbaharui"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BiayaAdmin  $biayaAdmin
     * @return \Illuminate\Http\Response
     */
    public function destroy(BiayaAdmin $biayaAdmin)
    {
        try {
            $biayaAdmin = BiayaAdmin::findOrFail($biayaAdmin->id);
            $biayaAdmin->delete();
            return response()->json(['message' => "Biaya Admin Berhasil Dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}