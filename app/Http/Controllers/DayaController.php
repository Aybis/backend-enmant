<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDayaRequest;
use App\Http\Requests\UpdateDayaRequest;
use App\Models\Daya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DayaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->search !== null) {

            $result = Daya::get();
        }else {
            $result = Daya::where('daya', 'like', '%'.$request->keyword.'%')->paginate(10);

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
     * @param  \App\Http\Requests\StoreDayaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::transaction(
                function() use ($request) {
                    $pelanggan = new Daya();
                    $pelanggan->daya = $request->daya;
                    $pelanggan->is_active = 1;
                    $pelanggan->save();
                }
            );
            return response()->json(['message' => "Daya Berhasil Ditambahkan"], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Daya  $daya
     * @return \Illuminate\Http\Response
     */
    public function show(Daya $daya)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Daya  $daya
     * @return \Illuminate\Http\Response
     */
    public function edit(Daya $daya)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDayaRequest  $request
     * @param  \App\Models\Daya  $daya
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Daya $daya)
    {

        $id = $daya == null ? '' : $daya->id;
        $validate = [
            'daya' => 'required|unique:dayas,daya,'.$daya->id,
        ];

        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
        ];
        
        $this->validate($request,$validate, $messages);
   
        try {
            $daya->daya = $request->daya;
            $daya->update();
            return response()->json(['message' => "Daya Berhasil Diperbaharui"], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Daya  $daya
     * @return \Illuminate\Http\Response
     */
    public function destroy(Daya $daya)
    {
        try {
            $daya = Daya::findOrFail($daya->id);
            $daya->delete();
            return response()->json(['message' => "Daya Berhasil Dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}