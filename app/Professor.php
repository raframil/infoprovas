<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
  protected $table = 'professores';
  public $timestamps = false;
  protected $fillable = ['nome', 'email'];

  public function provas()
  {
    return $this->belongsTo(\App\Prova::class, 'professor_id', 'id');
  }
}
