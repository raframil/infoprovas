<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Listar todos os cursos
Route::get('cursos', 'CursoController@index');
// Listar um curso
Route::get('curso/{id}', 'CursoController@show');

// Listar disciplinas de um curso
Route::get('disciplinas/{curso_id}', 'DisciplinaController@showByCurso');
// Listar TODAS disciplinas de um curso (autocomplete)
Route::get('disciplinas/all/{curso_id}', 'DisciplinaController@getAll');
// Listar uma disciplina
Route::get('disciplina/{curso_id}', 'DisciplinaController@show');
// Criar uma disciplina
Route::post('disciplinas', 'DisciplinaController@store');
// Atualizar uma disciplina
Route::put('disciplinas/{id}', 'DisciplinaController@update');
// Deletar disciplina
Route::delete('disciplinas/{id}', 'DisciplinaController@destroy');

// Listar provas de uma disciplina
Route::get('provas/{disciplina_id}', 'ProvaController@index');

// Exibir prova
Route::get('ver_prova/{id}', 'ProvaController@visualizarProva');
// Download prova
Route::get('baixar_prova/{id}', 'ProvaController@downloadProva');
// Upload de prova
Route::post('provas/upload', ['uses' => 'ProvaController@store']);


// Retorna todos os tipos de prova
Route::get('prova_tipos', 'ProvaTipoController@index');

// Retorna todos os professores
Route::get('professores', 'ProfessorController@index');
