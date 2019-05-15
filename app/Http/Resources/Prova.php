<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Prova extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return parent::toArray($request);
    /*return [
      'id' => $this->id,
      'nome' => $this->nome,
      'codigo' => $this->codigo,
      'curso_id' => $this->curso_id,
    ];*/
  }
}
