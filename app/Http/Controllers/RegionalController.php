<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegionalRequest;
use App\Http\Requests\UpdateRegionalRequest;
use App\Models\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regional = Regional::with('witel', 'witel.kwh_meter')->get();
        return response()->json($regional, 200);
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
     * @param  \App\Http\Requests\StoreRegionalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = [
            'nama' => 'required|unique:regionals',
            'alias' => 'required|unique:regionals',
        ];

        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
        ];
        
        $this->validate($request,$validate, $messages);

        try {
            DB::transaction(
                function() use ($request) {
                    $regional = new Regional();
                    $regional->nama = $request->nama;
                    $regional->alias = $request->alias;
                    $regional->is_active = 1;
                    $regional->save();
                }
            );
            return response()->json(['message' => "Regional Berhasil Ditambahkan"], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Regional  $regional
     * @return \Illuminate\Http\Response
     */
    public function show(Regional $regional)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Regional  $regional
     * @return \Illuminate\Http\Response
     */
    public function edit(Regional $regional)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRegionalRequest  $request
     * @param  \App\Models\Regional  $regional
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Regional $regional)
    {
        $id = $regional == null ? '' : $regional->id;
        $validate = [
            'nama' => 'required|unique:regionals,nama,'.$id,
            'alias' => 'required|unique:regionals,alias,'.$id,
        ];

        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
        ];
        
        $this->validate($request,$validate, $messages);

        try {
            $regional->nama = $request->nama;
            $regional->alias = $request->alias;
            $regional->update();
            return response()->json(['message' => "Regional Berhasil Diperbaharui"], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Regional  $regional
     * @return \Illuminate\Http\Response
     */
    public function destroy(Regional $regional)
    {
        try {
            $regional = Regional::findOrFail($regional->id);
            $regional->delete();
            return response()->json(['message' => "Regional Berhasil Dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $this->messageError($e)], 400);
        }
    }
}