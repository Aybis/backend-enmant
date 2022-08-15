<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTarifRequest;
use App\Http\Requests\UpdateTarifRequest;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarifController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        if($request->search !== null) {
            $result = Tarif::get();
            
        }else {
            $result = Tarif::where('tarif', 'LIKE', '%'.$request->keyword.'%')->paginate(10);
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
    * @param  \App\Http\Requests\StoreTarifRequest  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $validate = [
            'tarif' => 'required|unique:tarifs',
        ];
        
        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
        ];
        
        $this->validate($request,$validate, $messages);
        
        try {
            DB::transaction(
                function() use ($request) {
                    $tarif = new Tarif();
                    $tarif->tarif = $request->tarif;
                    $tarif->is_active = 1;
                    $tarif->save();
                }
            );
            
            return response()->json(['message' => "Tarif Berhasil Ditambahkan"], 201);
            
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Tarif  $tarif
    * @return \Illuminate\Http\Response
    */
    public function show(Tarif $tarif)
    {
        //
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Tarif  $tarif
    * @return \Illuminate\Http\Response
    */
    public function edit(Tarif $tarif)
    {
        //
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \App\Http\Requests\UpdateTarifRequest  $request
    * @param  \App\Models\Tarif  $tarif
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Tarif $tarif)
    {
        $validate = [
            'tarif' => 'required|unique:tarifs,tarif,'.$tarif->id,
        ];
        
        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
        ];
        
        $this->validate($request,$validate, $messages);
        
        try {
            $tarif->tarif = $request->tarif;
            $tarif->update();
            return response()->json(['message' => "Tarif Berhasil Diperbaharui"], 200);
            
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Tarif  $tarif
    * @return \Illuminate\Http\Response
    */
    public function destroy(Tarif $tarif)
    {
        try {
            $tarif = Tarif::findOrFail($tarif->id);
            $tarif->delete();
            return response()->json(['message' => "Tarif Berhasil Dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}