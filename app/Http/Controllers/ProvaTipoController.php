<?php

namespace App\Http\Controllers;

use App\ProvaTipo;
use Illuminate\Http\Request;
use App\Http\Resources\ProvaTipo as ProvaTipoResource;

class ProvaTipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cursos = ProvaTipo::get();
        return ProvaTipoResource::collection($cursos);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TipoProva  $tipoProva
     * @return \Illuminate\Http\Response
     */
    public function show(TipoProva $tipoProva)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoProva  $tipoProva
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoProva $tipoProva)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoProva  $tipoProva
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoProva $tipoProva)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TipoProva  $tipoProva
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoProva $tipoProva)
    {
        //
    }
}
