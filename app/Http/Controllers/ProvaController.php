<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Response;
use Storage;
use App\Http\Requests;
use App\Prova;
use App\Http\Resources\Prova as ProvaResource;

class ProvaController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index($disciplina_id)
  {
    //$provas = Prova::where('disciplina_id', $disciplina_id)->join('professores', 'professores.id', '=', 'provas.professor_id')->get();
    $provas = Prova::where('disciplina_id', $disciplina_id)->with('professores')->with('tipoProva')->with('disciplina')->get();
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
    /*$article = $request->isMethod('put') ? Article::findOrFail($request->article_id) : new Article;
        $article->id = $request->input('article_id');
        $article->title = $request->input('title');
        $article->body = $request->input('body');
        if ($article->save()) {
            return new ArticleResource($article);
        }*/ }
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
  {
    /* Get article
        $article = Article::findOrFail($id);
        if ($article->delete()) {
            return new ArticleResource($article);
        }*/ }

  public function visualizarProva($codigo, $id)
  {
    $prova = Prova::findOrFail($id);
    $filename = $prova->arquivo;
    $path = "public/storage/" . $codigo . "/" . $filename;
    //echo $path;

    if (!File::exists($path)) {
      abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
  }

  public function downloadProva($codigo, $id)
  {
    $prova = Prova::findOrFail($id);
    //pega o nome do arquivo e encaminha com o storage
    $filename = $prova->arquivo;
    $path = "storage/" . $codigo . "/" . $filename;

    if (!File::exists($path)) {
      abort(404);
    }
    $headers = array('Content-Type' => 'application/pdf');
    return response()->download($path, $filename, $headers);
  }
}
