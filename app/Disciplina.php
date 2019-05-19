<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
  public $timestamps = false;

  protected $fillable = ['nome', 'codigo', 'periodo', 'curso_id'];
}
