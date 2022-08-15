<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePICRequest;
use App\Http\Requests\UpdatePICRequest;
use App\Models\PIC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PICController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->search !== null){
            $result = PIC::get();
        }else {
            $result = PIC::where('nama_pic', 'LIKE', '%'.$request->keyword.'%')->paginate(10);
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
     * @param  \App\Http\Requests\StorePICRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validate = [
            'nama_pic' => 'required|unique:pics',
        ];

        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
        ];
        
        $this->validate($request,$validate, $messages);


        try {
            DB::transaction(
                function() use ($request) {
                    $kwh = new PIC();
                    $kwh->nama_pic = $request->nama_pic;
                    $kwh->is_active = 1 ;
                    $kwh->save();
                }
            );
            return response()->json(['message' => "PIC Berhasil Ditambahkan"], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PIC  $pIC
     * @return \Illuminate\Http\Response
     */
    public function show(PIC $pIC)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PIC  $pIC
     * @return \Illuminate\Http\Response
     */
    public function edit(PIC $pIC)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePICRequest  $request
     * @param  \App\Models\PIC  $pIC
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PIC $pic)
    {

        $id = $pic == null ? '' : $pic->id;
        $validate = [
            'nama_pic' => 'required|unique:pics,nama_pic,'.$id,
        ];

        $messages = [
            'unique' => ':Attribute sudah pernah dibuat',
            'required' => ':Attribute tidak boleh kosong',
        ];
        
        $this->validate($request,$validate, $messages);
        
        try {
            $pic->nama_pic = $request->nama_pic;
            $pic->update();
            return response()->json(['message' => "PIC Berhasil Diperbaharui"], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PIC  $pIC
     * @return \Illuminate\Http\Response
     */
    public function destroy(PIC $pic)
    {
        try {
            $pic = PIC::findOrFail($pic->id);
            $pic->delete();
            return response()->json(['message' => "PIC Berhasil Dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}