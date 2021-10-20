<?php

namespace App\Http\Controllers\Admin\configuracion\usuarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Model\UsuarioM;
use App\Model\LoginT;
use App\Model\Seniat;
use App\User;
use App\Model\Pais;
use App\Model\HistoricoT;
use Session;
use Image;
use Flash;

class UsuarioMController extends Controller
{
	const UPLOAD_PATH = 'medico';
    public function index(UsuarioM $model)
  	{   
  		Session::put('medico', null); 	
  		return view('admin.configuracion.usuarios.usuariosM.index', ['usuariosM' => $model->all()]);
  	}

  	public function create()
  	{
    	$sexo=['1'=> 'Mujer','2'=> 'Hombre','3'=> 'Otro'];
    	$prefijo=['1'=> 'V-','2'=> 'E-','3'=> 'J-'];
    	$estadoC=['1'=> 'Soltero(a)','2'=> 'Casado(a)','3'=> 'Divorciado(a)','4'=> 'Viudo(a)','5'=> 'Otro'];
    	$nacionalidad = Collection::make(Pais::select(['id_Pais','Pais'])->orderBy('Pais')->get())->pluck("Pais", "id_Pais");

    	return view('admin.configuracion.usuarios.usuariosM.create')->with(compact('sexo', 'prefijo', 'estadoC', 'nacionalidad')); 
  	}
	public function add(Request $request)
	{
		if($request->id == null){
		  	try {
		        $medico= new UsuarioM();
		        $medico->Nombres_Medico = ucfirst($request['nombre']);
		        $medico->Prefijo_CIDNI_id = $request['prefijo'];
		        $medico->Apellidos_Medicos = ucfirst($request['apellido']);
		        $medico->CIDNI = $request['cedula'];
		        $medico->Fecha_Nacimiento_Medico = $request['fechNac'];
		        $medico->Sexo_id = $request['sexo'];
		        $medico->Registro_MPPS = $request['registro'];
		        $medico->Numero_Colegio_de_Medico = $request['ncm'];
		        $medico->Status_Medico_id = 1;
		        $medico->Civil_id = $request['civil'];
		        $medico->Pais_id = $request['nacionalidad'];
		        $medico->save();
		        Session::put('medico', $medico);

		    //dd($medico->id);
		        $this->_procesarArchivo($request, $medico->id, 'medico');

		        Flash::success("Registro Agregado Correctamente");            
	    	return redirect()->route('usuario_m.edit', $medico->id);
		    } catch (\Illuminate\Database\QueryException $e) {
		        Flash::error('Ocurrió un error, por favor intente de nuevo');
		        return redirect()->route('usuario_m.create');
		    }
		}else{
			try{
	            $id = (int)$request->id;
	             UsuarioM::where('id_Medico', $id)->update([
	             	'Nombres_Medico' => ucfirst($request['nombre']),
			        'Prefijo_CIDNI_id' => $request['prefijo'],
			        'Apellidos_Medicos' => ucfirst($request['apellido']),
			        'CIDNI' => $request['cedula'],
			        'Fecha_Nacimiento_Medico' => $request['fechNac'],
			        'Sexo_id' => $request['sexo'],
			        'Registro_MPPS' => $request['registro'],
			        'Numero_Colegio_de_Medico' => $request['ncm'],
			        'Status_Medico_id' => 1,
			        'Civil_id' => $request['civil'],
			        'Pais_id' => $request['nacionalidad'],
	            ]);

	            $this->_procesarArchivo($request, $id, 'medico');
	            Flash::success("Registro Actualizado Correctamente");

	        }catch(\Illuminate\Database\QueryException $e) {
		        Flash::error('Ocurrió un error, por favor intente de nuevo'); 
	        }
	        return redirect()->route('usuario_m.edit', $id);
		}
	}
	public function edit($id)
	{
		$medico = UsuarioM::where('id_Medico', $id)->first();
		$login = LoginT::where('Status_Medico_id', $medico->id_Medico)->first();
		$seniat = Seniat::where('Medico_id', $medico->id_Medico)->first();

		Session::put('medico', $medico);

		//COMBOS
		$sexo=['1'=> 'Mujer'];
    	$prefijo=['1'=> 'V-'];
    	$estadoC=['1'=> 'Soltero(a)'];
    	$nacionalidad = Collection::make(Pais::select(['id_Pais','Pais'])->orderBy('Pais')->get())->pluck("Pais", "id_Pais");
		return view('admin.configuracion.usuarios.usuariosM.edit')->with(compact('medico','login','seniat','sexo','prefijo','estadoC','nacionalidad'));
	}

	public function login(Request $request)
	{
		if($request->idL == null){
			try {
		        $login= new LoginT();
		        $login->Usuario = ucfirst($request['nombre_usuario']);
		        $login->Correo = $request['correo'];
		        $login->Status_Medico_id = $request['id'];
		        $login->Contrasena = Hash::make($request['contrasena']);
		        $login->Nivel = $request['nivel'];
		        $login->save();

		        $login2= new User();
		        $login2->name = ucfirst($request['nombre_usuario']);
		        $login2->email = $request['correo'];
		        $login2->password = Hash::make($request['contrasena']);
		        $login2->status = 1;
		        $login2->id_usuario = $request['id'];
		        $login2->save();

				Flash::success("Registro Agregado Correctamente");            
		    } catch (\Illuminate\Database\QueryException $e) {
		        Flash::error('Ocurrió un error, por favor intente de nuevo');    
		    }

	    	return redirect()->route('usuario_m.edit', $request['id']);
		}else{
			
	        $id = (int)$request->id;
			$login= User::where('id_usuario', $id)->first();
	        $fecha= date('Y-m-d');

	         if(password_verify($request['contrasena'], $login->password)){
	         	Flash::error('Debe ingresar una contraseña distinta a las anterior');
	         	return redirect()->route('usuario_m.edit', $id);
	         }else{
				try{
		            LoginT::where('Status_Medico_id', $id)->update([
		             	'Usuario' => ucfirst($request['nombre_usuario']),
				        'Correo' => $request['correo'],
				        'Status_Medico_id' => $request['id'],
				        'Contrasena' => Hash::make($request['contrasena']),
				        'Nivel' => $request['nivel'],
		            ]);

		            User::where('id_usuario', $id)->update([
		             	'name' => ucfirst($request['nombre_usuario']),
				        'email' => $request['correo'],
				        'password' => Hash::make($request['contrasena']),
				        'status' => 1,
				        'id_usuario' => $request['id'],
		            ]);

		            $loginh= new HistoricoT();
			        $loginh->Login_Tranajador_id = $login->id;
			        $loginh->Old_Constrasena = Hash::make($login->password);
			        $loginh->Fecha = $fecha;
			        $loginh->Medico_id = $id;
			        $loginh->Correo = $login->email;
			        $loginh->Nota = '';
			        $loginh->save();

		            Flash::success("Registro Actualizado Correctamente");

		        }catch(\Illuminate\Database\QueryException $e) {
			        Flash::error('Ocurrió un error, por favor intente de nuevo'); 
		        }
		        return redirect()->route('usuario_m.edit', $id);
			}
		}
	}


	public function seniat(Request $request)
	{
		if($request->idS == null){			
			try {
		        $seniat= new Seniat();
		        $seniat->RIF = $request['rif'];
		        $seniat->Direccion = $request['direccion'];
		        $seniat->Medico_id = $request['id'];
		        $seniat->Fecha = $request['fecha'];
		        $seniat->save();

				Flash::success("Registro Agregado Correctamente"); 
		    } catch (\Illuminate\Database\QueryException $e) {
		        Flash::error('Ocurrió un error, por favor intente de nuevo');    
		    }
           
	    }else{
	    	try{
	    	 	Seniat::where('Medico_id', $request->id)->update([
	             	'RIF' => $request['rif'],
			        'Direccion' => $request['direccion'],
			        'Fecha' => $request['fecha'],
	            ]);

	            Flash::success("Registro Actualizado Correctamente");

	        }catch(\Illuminate\Database\QueryException $e) {
		        Flash::error('Ocurrió un error, por favor intente de nuevo'); 
	        }
	    }
	        return redirect()->route('usuario_m.edit', $request->id);
	}

	private function _procesarArchivo(Request $request, $id, $tipo)
    {
        if ($request->hasFile('avatar')) {
           $tmp = $request->file('avatar');
           
           $nombre= str_replace(' ', '', $request->input("cedula"));
        
            if ($tmp->isValid()) {
                $extension = $tmp->extension();
                $nombreArchivo = sprintf('%s_%s_%s.%s', $tipo, $id, $nombre, $extension);
                $this->_eliminarArchivo($nombreArchivo);
                $ubicacion = $tmp->storeAs(
                    self::UPLOAD_PATH,
                    $nombreArchivo
                );
                    $ubicacion = $this->separadorDirectorios($ubicacion);			       
                    UsuarioM::where('id_Medico', $id)->update(['Foto_Medico'=>$ubicacion]);
            }
        }
    }
    private function _eliminarArchivo($nombreArchivo){
        $archivo = self::UPLOAD_PATH.'/'.$nombreArchivo;
        Storage::disk('public')->delete([$archivo.'.jpg']);
        Storage::disk('public')->delete([$archivo.'.jpeg']);
        Storage::disk('public')->delete([$archivo.'.png']);
        Storage::disk('public')->delete([$archivo.'.gif']);
        Storage::disk('public')->delete([$archivo.'.pdf']);
    }

    public function separadorDirectorios($path){

      return str_replace(['\\','/'], DIRECTORY_SEPARATOR, $path);
    }
	public function destroy(Request $request)
    {
       $id = (int)$request->input('id');
       UsuarioM::where('id_Medico', $id)->update(['Status_Medico_id' => 2]);
       User::where('id_usuario', $id)->update(['status' => 0]);

       Flash::success('Registro eliminado correctamente');
         
      return redirect()->route('usuario_m');
    }
}
