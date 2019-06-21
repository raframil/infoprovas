<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Response;
use Storage;
use Validator;
use App\Disciplina;
use App\Curso;
use App\Http\Requests;
use App\Prova;
use App\Http\Resources\Prova as ProvaResource;
use PHPUnit\Framework\Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProvaController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($disciplina_id)
  {
    $provas = Prova::where('disciplina_id', $disciplina_id)
      ->where('ativo', 'S')
      ->with('professores')
      ->with('tipoProva')
      ->with('disciplina')
      ->paginate(15);
    return ProvaResource::collection($provas);
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
      'upload' => 'required',
      'ano' => 'required',
      'periodo' => 'required',
      'disciplina_id' => 'required',
      'professor_id' => 'required',
      'tipo_prova_id' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json(['error' => $validator->errors()], 401);
    }
    $disciplina = Disciplina::findOrFail($request->disciplina_id);
    $curso = Curso::findOrFail($disciplina->curso_id);

    $uploadedFile = $request->file('upload');
    $filename = md5(time()) . '.pdf';

    $pasta_curso = $curso->nome;
    $pasta_disciplina = $disciplina->id . $disciplina->codigo;
    $fullPath = 'provas/' . $curso->campus . "/" . $pasta_curso . "/" . $pasta_disciplina . "/";

    if (!empty($uploadedFile)) {
      try {
        Storage::disk('local')->putFileAs(
          $fullPath,
          $uploadedFile,
          $filename
        );
        $prova_upload = Prova::create([
          'arquivo' => $fullPath . $filename,
          'ano' => $request->ano,
          'periodo' => $request->periodo,
          'disciplina_id' => $request->disciplina_id,
          'professor_id' => $request->professor_id,
          'tipo_prova_id' => $request->tipo_prova_id,
          'ativo' => 'S'
        ]);
        //return Storage::url($uploadedFile);
        return response()->json(['message' => 'Sucesso', 'prova' => $prova_upload], 201);
      } catch (FileException $e) {
        return $e;
      }
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $prova = Prova::findOrFail($id);
    return new ProvaResource($prova);
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  { }


  public function visualizarProva($id)
  {
    $prova = Prova::findOrFail($id);
    $filePath = $prova->arquivo;

    if (!Storage::exists($filePath)) {
      return response()->json(['message' => 'Erro', 'resultado' => 'Arquivo não encontrado'], 404);
    } else {
      return Storage::response($filePath);
    }
  }


  public function downloadProva($id)
  {
    $prova = Prova::findOrFail($id);
    $filePath = $prova->arquivo;

    if (!Storage::exists($filePath)) {
      return response()->json(['message' => 'Erro', 'resultado' => 'Arquivo não encontrado'], 404);
    } else {
      $headers = array('Content-Type' => 'application/pdf');
      return Storage::download($filePath, null, $headers);
    }
  }
}
