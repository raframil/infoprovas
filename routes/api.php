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
// Listar uma disciplina
Route::get('disciplina/{curso_id}', 'DisciplinaController@show');

// Criar uma disciplina
Route::post('disciplinas', 'DisciplinaController@store');
Route::put('disciplinas/{disciplina}', 'DisciplinaController@update');


// Listar provas de uma disciplina
Route::get('provas/{disciplina_id}', 'ProvaController@index');

// Exibir prova
Route::get('ver_prova/{codigo}/{id}', 'ProvaController@visualizarProva');

// Download prova
Route::get('baixar_prova/{codigo}/{id}', 'ProvaController@downloadProva');
