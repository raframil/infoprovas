<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ProvaTipo extends Model
{
  protected $table = 'prova_tipos';
  public function provas()
  {
    return $this->belongsTo(\App\Prova::class, 'tipo_prova_id', 'id');
  }
}
