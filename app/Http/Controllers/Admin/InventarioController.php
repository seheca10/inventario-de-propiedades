<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use Illuminate\Http\Request;
use App\Models\Condominio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use App\Models\Propiedad;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventarios = Inventario::all();

        return view('inventarios.index', compact('inventarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(string $tipo_de_propiedad)
    {
        $fecha_de_hoy = Carbon::now()->toDayDateTimeString();
        $condominios = Condominio::all();

        return view('inventarios.create', compact('fecha_de_hoy', 'condominios', 'tipo_de_propiedad'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function show(Inventario $inventario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventario $inventario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventario $inventario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventario $inventario)
    {
        //
    }

    public function informacionPropiedad(Inventario $inventario)
    {
        return view('inventarios.informacion-propiedad', compact('inventario'));
    }

    public function informacionHabitacion(Propiedad $propiedad)
    {
        return view('inventarios.habitaciones.create', compact('propiedad'));
    }

    public function observacionPropiedad(Propiedad $propiedad) {
        return view('inventarios.propiedad.observaciones', compact('propiedad'));
    }

    public function verObservaciones(Inventario $inventario)
    {
        return view('inventarios.observaciones.show', compact('inventario'));
    }

    public function informacionBanos(Propiedad $propiedad)
    {
        return view('inventarios.banos.create', compact('propiedad'));
    }
}