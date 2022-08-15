<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWitelRequest;
use App\Http\Requests\UpdateWitelRequest;
use App\Models\Witel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WitelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreWitelRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validate = [
            'nama' => 'required|unique:witels',
            'alias' => 'required|unique:witels',
        ];

        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
        ];
        
        $this->validate($request,$validate, $messages);

        try {
            DB::transaction(
                function() use ($request) {
                    $witel = new Witel();
                    $witel->nama = $request->nama;
                    $witel->alias = $request->alias;
                    $witel->id_regional = $request->id_regional;
                    $witel->save();
                }
            );
            return response()->json(['message' => "Witel Berhasil Ditambahkan"], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Witel  $witel
     * @return \Illuminate\Http\Response
     */
    public function show(Witel $witel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Witel  $witel
     * @return \Illuminate\Http\Response
     */
    public function edit(Witel $witel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWitelRequest  $request
     * @param  \App\Models\Witel  $witel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Witel $witel)
    {
        
        try {
            $witel->nama = $request->nama;
            $witel->alias = $request->alias;
            $witel->id_regional = $request->id_regional;
            $witel->update();
            return response()->json(['message' => "Witel Berhasil Diperbaharui"], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Witel  $witel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Witel $witel)
    {
        try {
            $witel = Witel::findOrFail($witel->id);
            $witel->delete();
            return response()->json(['message' => "Witel Berhasil Dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $this->messageError($e)], 400);
        }
    }
}