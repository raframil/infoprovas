<?php

namespace App\Http\Controllers;

use App\Professor;
use Illuminate\Http\Request;
use App\Http\Resources\Professor as ProfessorResource;
use Validator;

class ProfessorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $professores = Professor::get();
        return ProfessorResource::collection($professores);
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
        $validator = Validator::make($request->all(), [
            'nome' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $professor = Professor::create($request->all());
        return response()->json(['message' => 'Sucesso', 'professor' => $professor], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Professor  $professor
     * @return \Illuminate\Http\Response
     */
    public function show(Professor $professor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Professor  $professor
     * @return \Illuminate\Http\Response
     */
    public function edit(Professor $professor)
    { }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Professor  $professor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $professor = Professor::findOrFail($id);
        $professor->update($request->all());
        return response()->json(['message' => 'Sucesso', 'professor' => $professor], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Professor  $professor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $professor = Professor::findOrFail($id);
        try {
            if ($professor->delete()) {
                return response()->json(['message' => 'Sucesso', 'professor' => $professor], 200);
            } else {
                return response()->json(['message' => 'Erro'], 404);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Não é possível deletar um professor que possui provas registradas', 'success' => 'false'], 405);
        }
    }
}
