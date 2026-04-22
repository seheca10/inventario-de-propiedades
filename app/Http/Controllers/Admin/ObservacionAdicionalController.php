<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ObservacionAdicional;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ObservacionAdicionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $observaciones = ObservacionAdicional::latest()->get();

        return view('inventarios.observaciones.index', compact('observaciones'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ObservacionAdicional::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FirmaInventario  $firmaInventario
     * @return \Illuminate\Http\Response
     */
    public function show(ObservacionAdicional $observacion)
    {
        dd($observacion);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FirmaInventario  $firmaInventario
     * @return \Illuminate\Http\Response
     */
    public function edit(FirmaInventario $firmaInventario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FirmaInventario  $firmaInventario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FirmaInventario $firmaInventario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FirmaInventario  $firmaInventario
     * @return \Illuminate\Http\Response
     */
    public function destroy(FirmaInventario $firmaInventario)
    {
        //
    }
}
