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
Route::get('disciplinas/{curso_id}', 'DisciplinaController@index');
Route::get('disciplina/{curso_id}', 'DisciplinaController@show');

// Listar provas de uma disciplina
Route::get('provas/{disciplina_id}', 'ProvaController@index');
