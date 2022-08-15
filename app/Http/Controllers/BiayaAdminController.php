<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBiayaAdminRequest;
use App\Http\Requests\UpdateBiayaAdminRequest;
use App\Models\BiayaAdmin;

class BiayaAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = BiayaAdmin::get();

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
    public function store(StoreBiayaAdminRequest $request)
    {
        //
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
    public function update(UpdateBiayaAdminRequest $request, BiayaAdmin $biayaAdmin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BiayaAdmin  $biayaAdmin
     * @return \Illuminate\Http\Response
     */
    public function destroy(BiayaAdmin $biayaAdmin)
    {
        //
    }
}