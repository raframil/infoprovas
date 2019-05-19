<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
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
        return response()->json(['message' => 'Success', 'disciplina' => $disciplina], 200);
    }

    public function update(Request $request, $id)
    {
        $disciplina = Disciplina::findOrFail($id);
        $disciplina->update($request->all());
        //return $disciplina;
        return response()->json(['message' => 'Update successfully', 'disciplina' => $disciplina], 200);
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
        /* Get article
        $article = Article::findOrFail($id);
        if ($article->delete()) {
            return new ArticleResource($article);
        }*/ }

    public function index()
    {
        $disciplinas = Disciplina::get();
        return DisciplinaResource::collection($disciplinas);
    }
}
