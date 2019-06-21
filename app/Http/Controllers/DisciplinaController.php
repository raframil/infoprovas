<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Prova;
use Validator;
use App\Disciplina;
use App\Http\Resources\Disciplina as DisciplinaResource;

class DisciplinaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showByCurso($curso_id)
    {
        $disciplinas = Disciplina::where('curso_id', $curso_id)->paginate(15);
        //seleciona o numero de provas por disciplina
        foreach ($disciplinas as $key => $disciplina) {
            $num_provas = Prova::where("disciplina_id", $disciplina->id)->where("ativo", "S")->count();
            $disciplina->num_provas = $num_provas;
        }
        return DisciplinaResource::collection($disciplinas);
    }

    public function getAll($curso_id)
    {
        $disciplinas = Disciplina::where('curso_id', $curso_id)->get();
        return DisciplinaResource::collection($disciplinas);
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
            'codigo' => 'required',
            'periodo' => 'required',
            'curso_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $disciplina = Disciplina::create($request->all());
        return response()->json(['message' => 'Sucesso', 'disciplina' => $disciplina], 201);
    }

    public function update(Request $request, $id)
    {
        $disciplina = Disciplina::findOrFail($id);
        $disciplina->update($request->all());
        return response()->json(['message' => 'Sucesso', 'disciplina' => $disciplina], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $disciplina = Disciplina::findOrFail($id);
        return new DisciplinaResource($disciplina);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $disciplina = Disciplina::findOrFail($id);
        if ($disciplina->delete()) {
            return response()->json(['message' => 'Sucesso', 'disciplina' => $disciplina], 200);
        } else {
            return response()->json(['message' => 'Erro'], 404);
        }
    }

    public function index()
    {
        $disciplinas = Disciplina::get();
        return DisciplinaResource::collection($disciplinas);
    }
}
