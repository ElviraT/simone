<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DireccionPaciente extends Model
{
   protected $table = 'direcciones_pacientes';
   protected $primary_key = 'id_Direccion_Paciente';
   public $timestamps = false;

}
