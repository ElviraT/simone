<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection as Collection;
use Illuminate\Http\Request;
use App\Model\UsuarioP;
use App\Model\UsuarioPE;
use App\Model\UsuarioM;
use App\Model\Especialidad;
use App\Model\Antecedente;
use App\Model\Anamenesi;
use App\Model\Citas;
use DB;

class ConsultaOController extends Controller
{
	public function __construct()
    {
      $this->middleware('can:consultao')->only('index');
      $this->middleware('can:consultao.add')->only('add');
      $this->middleware('can:consultao.edit')->only('edit');
      $this->middleware('can:consultao.destroy')->only('destroy');
    }

   public function index()
   {
   		$medico = UsuarioM::where('id_Medico', auth()->user()->id_usuario)->first(); 
   		$especialidad = Especialidad::
	       select('especialidades_medicas.id_Especialidad_Medica AS id', 'especialidades_medicas.Espacialiadad_Medica AS name')
	       ->join('control_especialidades', 'especialidades_medicas.id_Especialidad_Medica','control_especialidades.Especialidades_Medicas_id')
	       ->where('control_especialidades.Medico_id', auth()->user()->id_usuario)
	       ->first();
   		$pacientes=Collection::make(UsuarioP::select(['id_Paciente',DB::raw('CONCAT(Nombres_Paciente, " ", Apellidos_Paciente) AS Nombre')])->where('Status_id',1)->orderBy('Nombres_Paciente')->pluck("Nombre", "id_Paciente"));
		  $pacientesE = Collection::make(UsuarioPE::
             select(['pacientes_especiales.id_Pacientes_Especiales AS id', DB::raw('CONCAT(pacientes_especiales.Nombre_Paciente_Especial, " ",pacientes_especiales. Apellido_Paciente_Especial) AS name')])
             ->join('usuarios_pacientes', 'pacientes_especiales.Paciente_id','usuarios_pacientes.id_Paciente')
             ->get())->pluck('name','id'); 
      
    	return view('admin.consultaO.index')->with(compact('pacientes','pacientesE','medico','especialidad'));
   }

   public function antecedentes()
   {
      if($_POST['id_antecedente'] == 0){
         try {
                $consultao= new Antecedente();
                $consultao->Paciente_Id = $_POST['id_paciente'];
                $consultao->Paciente_Especial_id = $_POST['id_pacienteE'];
                $consultao->Medico_id = $_POST['id_medico'];
                $consultao->Fecha = $_POST['fecha'];
                $consultao->Control_Historia_Medico_id =$_POST['control'];
                $consultao->id_Status = 1;
                $consultao->Personal = $_POST['personales'];;
                $consultao->Familiar = $_POST['familiares'];
                $consultao->Farmacologico = $_POST['farmacologicos'];
                $consultao->Examen_Fisico = $_POST['fisico'];
                $consultao->Imprecion_Diagnostica = $_POST['impresion'];
                $consultao->save(); 

                $info= 'Registro Agregado Correctamente';     
             
            } catch (\Illuminate\Database\QueryException $e) {
                $info= 'Ocurrio un error intente de nuevo'; 
            }
      }else{
        try{
            $id = (int)$_POST['id_antecedente'];
             Antecedente::where('id_antecedente', $id)->update([
                'Fecha'=> $_POST['fecha'],
                'Personal' =>$_POST['personales'],
                'Familiar'=> $_POST['familiares'],
                'Farmacologico'=> $_POST['farmacologicos'],
                'Examen_Fisico' =>$_POST['fisico'],
                'Imprecion_Diagnostica'=> $_POST['impresion'],
            ]);

             $info= 'Registro Actualizado Correctamente';
        }catch(\Illuminate\Database\QueryException $e){                    
            $info= 'Ocurrio un error intente de nuevo';
        }
      }
        return $info;
   }
   public function anamenesis()
   {
      DB::beginTransaction();
     try {
            $consulta2= new Anamenesi();
            $consulta2->Paciente_Id = $_POST['id_paciente'];
            $consulta2->Paciente_Especial_id = $_POST['id_pacienteE'];
            $consulta2->Medico_id = $_POST['id_medico'];
            $consulta2->Fecha = $_POST['fecha'];
            $consulta2->Control_Historia_Medico_id =$_POST['control'];
            $consulta2->Enfermedad_Actual = $_POST['Eactual'];
            $consulta2->Origen = $_POST['origen'];;
            $consulta2->Hallazgo = $_POST['hallazgo'];
            $consulta2->Plan_Tratamiento = $_POST['tratamiento'];
            $consulta2->Diagnostico_Definitivo = $_POST['diagnostico'];
            $consulta2->Pronostico = $_POST['pronostico'];
            $consulta2->id_Status = 1;
            $consulta2->Peso = $_POST['peso'];
            $consulta2->Talla = $_POST['estatura'];
            $consulta2->save(); 

            $info= 'Registro Agregado Correctamente';     
         
         $control= ControlHM::where('id_Control_Historia_Medica',$_POST['control'])->update([
                'control'=> 1,
            ]);
       DB::commit();
        } catch (Exception $e) {
          DB::rollback();
            $info='Ocurrio un error intente de nuevo'; 
        }
        return $info;
   }

    public function buscar_paciente(Request $request){
     $paciente = empty($request->input('paciente')) ? null : $request->input('paciente');
     $pacienteE = empty($request->input('pacienteE')) ? null : $request->input('pacienteE');
     $medico = empty($request->input('medico')) ? null : $request->input('medico');
     $datos = [];
     $antecedentes= [];
        
      $cita= Citas::where('Paciente_id',$paciente)
                  ->where('Paciente_Especial_id', $pacienteE)
                  ->where('Medico_id', $medico)
                  ->whereDate('start', date('Y-m-d'))//date('Y-m-d'))
                  ->whereTime('start', date('H:00'))//date('h:00'))
                  ->first();   
                 // dd($paciente, $pacienteE, $medico, date('Y-m-d'), date('H:00'));      
      if($cita){
        if ($pacienteE != null) {
           $datos = UsuarioPE::select('pacientes_especiales.Nombre_Paciente_Especial', 'pacientes_especiales.Apellido_Paciente_Especial', 'pacientes_especiales.Fecha_Nacimiento_Paciente_Especial', 'sexos.Sexo','control_historia_medicas.id_Control_Historia_Medica')
               ->join('sexos', 'sexos.id_Sexo','pacientes_especiales.Sexo_id')
               ->join('control_historia_medicas', 'control_historia_medicas.Paciente_Especial_id','pacientes_especiales.id_Pacientes_Especiales')
               ->where('pacientes_especiales.id_Pacientes_Especiales',$pacienteE)
               ->where('control_historia_medicas.cerrado',0)
               ->first();       
        }else{
           $datos = UsuarioP::select('usuarios_pacientes.Nombres_Paciente', 'usuarios_pacientes.Apellidos_Paciente', 'usuarios_pacientes.Fecha_Nacimiento_Paciente', 'sexos.Sexo','control_historia_medicas.id_Control_Historia_Medica')
               ->join('sexos', 'sexos.id_Sexo','usuarios_pacientes.Sexo_id')
               ->join('control_historia_medicas', 'control_historia_medicas.Paciente_id','usuarios_pacientes.id_Paciente')
               ->where('usuarios_pacientes.id_Paciente',$paciente)
               ->where('control_historia_medicas.cerrado',0)
               ->first();
        }   
        $antecedentes= Antecedente::where('Paciente_Id', $paciente)->first();     
      }else{
        $datos= 'No tiene cita hoy a esta hora';
      }

      return response()->json([$datos, $antecedentes, $cita]);
    }
}
