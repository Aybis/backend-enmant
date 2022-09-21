<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->search !== null) {
            $pelanggan = Pelanggan::where('nama_pelanggan', 'LIKE', '%' . $request->searching . '%')->get();
        } else {
            $pelanggan = Pelanggan::with('kwhMeter', 'prabayars', 'pascabayars')
                ->withCount(['kwhMeter', 'prabayars', 'pascabayars'])
                ->where('nama_pelanggan', 'LIKE', '%' . $request->keyword . '%')->paginate(10);
        }

        return response()->json($pelanggan, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePelangganRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validate = [
            'nama_pelanggan' => 'required|unique:pelanggans',
        ];

        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
        ];

        $this->validate($request, $validate, $messages);

        try {
            DB::transaction(
                function () use ($request) {
                    $pelanggan = new Pelanggan();
                    $pelanggan->nama_pelanggan = $request->nama_pelanggan;
                    $pelanggan->is_active = 1;
                    $pelanggan->save();
                }
            );
            return response()->json(['message' => "Pelanggan Berhasil Ditambahkan"], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePelangganRequest  $request
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {

        $id = $pelanggan == null ? '' : $pelanggan->id;
        $validate = [
            'nama_pelanggan' => 'required|unique:pelanggans,nama_pelanggan,' . $id,
        ];

        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
        ];

        $this->validate($request, $validate, $messages);

        try {
            $pelanggan->nama_pelanggan = $request->nama_pelanggan;
            $pelanggan->update();
            return response()->json(['message' => "Pelanggan Berhasil Diperbaharui"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pelanggan $pelanggan)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($pelanggan->id);
            $pelanggan->delete();
            return response()->json(['message' => "Pelanggan Berhasil Dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $this->messageError($e)], 400);
        }
    }
}
