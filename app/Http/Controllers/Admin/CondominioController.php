<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Condominio;

class CondominioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $condominios = Condominio::all()->sortBy('nombre');
        
        return view('admin.condominios.index', compact('condominios'));
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
        $request->validate();

        Condominio::create($request->validate([
            'nombre' => ['required','string'],
        ]));
            
        return redirect()->route('condominios.index')
                        ->with('success','Condominio creado exitósamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $condominio = Condominio::find($id);

        $condominio->update([
            'nombre' => $request->input('nombre')
            ]
        );
        
        return redirect()->route('condominios.index')
                    ->with('success','Condominio actualizado exitósamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $condominio = Condominio::find($id);

        $condominio->delete();

        return redirect()->route( 'condominios.index' )
               ->with( 'danger', 'Condominio eliminado de manera satisfactoria');
    }
}
