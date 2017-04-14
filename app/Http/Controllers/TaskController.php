<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use Carbon\Carbon;
use App\User;
use App\Calendar;
use App\Absence;
use Validator;
use App\TaskLog;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskController extends Controller{
	public function index(){
		//verificacion de usuario/rol
		return view('modules.tasks.list')->with('tasks', Task::all());
	}

	public function showDataForm(){
		//verificacion de usuario/rol
		return view('modules.tasks.forms.data-form')->with(['users' => User::all(), 'types' => Task::getEnumValues('type')]);
	}

	public function register(Request $request){
		//verificacion de usuario/rol
		Validator::make($request->input(), [
			'title' => 'required',
			'priority' => 'required',
			'complexity' => 'required',
			'type' => 'required',
			'estimated_date' => 'required|date_format:d/m/Y|after:yesterday',#Aqui verificacion de calendario
			'users' => 'required',
			'details' => 'min:10|max:255'
		])->validate();

		foreach ($request->users as $user) {
			$task = new task();
			$task->title = $request->title;
			$task->priority = $request->priority;
			$task->complexity = $request->complexity;
			$task->type = $request->type;
			$task->estimated_date = Carbon::createFromFormat('d/m/Y',$request->estimated_date);
			$task->details = $request->details;
			$task->user_id = $user;
			$task->save();
		}

		#Aqui Posponer Tareas
		/*
		$tareasPorFecha = $usuario->getTareasPorFecha($Opermrepo->fecIni, $Opermrepo->fecFin);
		if (!is_null($tareasPorFecha)) {
			foreach ($tareasPorFecha as $tarea) {
				$tarea->fecEst = Ccalendario::getProxima($Opermrepo->fecFin);
				$tarea->save();
			}
		}
		*/
		$tasks = Task::all();
		\Session::push('success', true);
		return redirect("tareas/listar")->with(['tasks' => $tasks]);
	}

	public function showUpdateForm(Request $request){
		$task = Task::find($request->id);
		if (!$task) return view('errors.404');
		//verificacion de usuario/rol
		return view('modules.tasks.forms.data-form')->with(['task' => $task, 'users' => User::getUsersByOcupation(), 'types' => Task::getEnumValues('type')]);
	}

	public function update(Request $request){
		$task = Task::find($request->id);
		if (!$task) return view('errors.404');
		//verificacion de usuario/rol
		Validator::make($request->input(), [
			'title' => 'required',
			'priority' => 'required',
			'complexity' => 'required',
			'type' => 'required',
			'estimated_date' => 'required|date_format:d/m/Y|after:yesterday', #Aqui verificacion de calendario
			'details' => 'min:10|max:255'
		])->validate();

		$task->title = $request->title;
		$task->priority = $request->priority;
		$task->complexity = $request->complexity;
		$task->type = $request->type;
		$task->estimated_date = Carbon::createFromFormat('d/m/Y',$request->estimated_date);
		$task->details = $request->details;
		$task->status = Task::DEFAULT_STATUS;
		$task->save();

		$tasks = Task::all();
		\Session::push('success', true);
		return redirect("tareas/listar")->with(['tasks' => $tasks]);
	}

    public function personalList(Request $request){

    }
    public function view(Request $request){
		$task = Task::find($request->id);
		if (!$task) return view('errors.404');
		//verificacion de usuario/rol
		$task->taskLogs()->paginate(5);
		$log = $task->taskLogs()->lastest()->first();
		return view('modules.tasks.view')->with(['task' => $task, 'statuses' => Task::getEnumValues('status'), 'log' => $log]);
    }

	public function transact(Request $request){
		//verificacion de usuario/rol
		$task = Task::find($request->task_id);
		if (!$task) return view('errors.404');

		Validator::make($request->input(), [
			'status' => 'required',
			'details' => 'min:10|max:255'
		])->validate();

		$log = new TaskLog;
	}


}
/*


public function getVer(Request $request, $idTar){
	$Otarea = Ctarea::find($idTar);
	if(!Auth::user()->tieneAccion('tareas.listar') && $Otarea->idUsu != Auth::user()->idUsu)
		return redirect('errores/acceso-negado');
	if($Otarea->idUsu == Auth::user()->idUsu && !$Otarea->visto){
		$Otarea->visto = true;
		$Otarea->save();
	}
	$bitacoras = CBitaTarea::all();
	$Otarea->usuarioResponsable = Cusuario::findOrFail($Otarea->idUsu);
	if($Otarea)
		$bitacora = CBitaTarea::where('idTar', '=', $Otarea->idTar)->paginate(5);
		return view('tareas/ver')->with('Otarea', $Otarea)->with('bitacora', $bitacora);
	return redirect('tareas/listar');
}
public function getBitacora(Request $request){
	$Otarea = Ctarea::find($request->idTar);
	if(!(Auth::user()->tieneAccion('tareas.listar'))
			&& $Otarea->idUsu != Auth::user()->idUsu
			&& !(Auth::user()->tieneRolPorNombre('Jefe de Departamento')))
		return redirect('errores/acceso-negado');
	$arrEstados = ['Asignada','Revision','Cumplida','Cancelada','Diferida','Retrasada'];
	return view('tareas.bitacora')->with('Otarea', $Otarea)->with('arrEstados', $arrEstados);
}
public function postBitacora(Request $request){
	$Otarea = Ctarea::findOrFail($request->idTar);
	if(!(Auth::user()->tieneAccion('tareas.listar')) && $Otarea->idUsu != Auth::user()->idUsu)
		return redirect('errores/acceso-negado');

	$Otarea->estTar = $request->input('status');
	$Otarea->save();

	$Obitacora = New CBitaTarea;
	$Obitacora->idTar = $Otarea->idTar;
	$Obitacora->detalle = $request->input("incidencia");
	$Obitacora->nombreUsu = Auth::user()->getNombreCompleto();
	$Obitacora->estado = $Otarea->estTar;
	$Obitacora->fecInc = Carbon::now('America/Caracas');
	$Obitacora->save();

	$arrEstados = ['Asignada','Revision','Cumplida','Cancelada','Diferida','Retrasada'];
	return redirect('tareas/listar')->with('estado', 'incidencia');
}


public function postModificar(Request $request){
	//dd($request);
	$Otarea=Ctarea::findOrFail($request->get('idTar'));
	if( !is_null($request->input("title")))
		$Otarea->titulo = $request->get("title");

	if( !is_null($request->input("deliverdate")))
		$Otarea->fecEst=$request->input("deliverdate");

	if( !is_null($request->input("details")))
		$Otarea->detalle=$request->input("details");

	if( !is_null($request->input("priority")))
		$Otarea->prioridad=$request->input("priority");

	if( !is_null($request->input("complexity")))
		$Otarea->complejidad=$request->input("complexity");

	if( !is_null($request->input("tipoTarea")))
		$Otarea->tipTar=$request->input("tipoTarea");

	if( !is_null($request->input("responsable")))
		$Otarea->idUsu=$request->input("responsable");
	$Otarea->save();

	$tareas = Ctarea::all();
	foreach($tareas as $ItemOtarea){
		$ItemOtarea->usuarioResponsable = Cusuario::findOrFail($ItemOtarea->idUsu);
	}
	return redirect("/tareas/listar")
			->with('tareas', $tareas)
			->with('estado', 'modificada');
}
public function getEliminar(){
	return redirect('/tareas/listar')->with('estado', 'no-seleccionado');
}
public function postEliminar(Request $request){
	if(!(\Auth::user()->tieneAccion('tareas.eliminar')))
		return redirect('errores/acceso-negado');
	$Oeliminada = Ctarea::find($request->get('idTar'));
	$Oeliminada->delete();
	$tareas=Ctarea::all();
	foreach($tareas as $tarea){
		$tarea->usuarioResponsable = Cusuario::findOrFail($tarea->idUsu);
	}
	return redirect("tareas/listar")->with('tareas',$tareas)->with('estado', 'eliminada');
}
*/
