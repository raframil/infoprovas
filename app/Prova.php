<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Prova extends Model
{
  protected $table = 'provas';

  public function professores()
  {
    return $this->hasOne(\App\Professor::class, 'id', 'professor_id');
  }

  public function disciplina()
  {
    return $this->belongsTo(\App\Disciplina::class, 'disciplina_id', 'id');
  }


  public function tipoProva()
  {
    return $this->hasOne(\App\ProvaTipo::class, 'id', 'tipo_prova_id');
  }
}
