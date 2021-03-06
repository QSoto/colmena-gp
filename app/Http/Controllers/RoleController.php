<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Auth;
use App\Role;
use App\User;
use App\Permission;

class RoleController extends Controller{

    public function index(Request $request){
        return view('modules.roles.list')->with('roles', Role::getRolesByLevel());
    }
    public function showDataForm(Request $request){
        return view('modules.roles.forms.data-form')->with(['permissions' => Permission::getByCategory()]);
    }
    public function showUpdateForm(Request $request){
		$role = Role::find($request->id);
		if($role == null){
			return view('errors.404');
		}
        return view('modules.roles.forms.data-form')->with(['role' => $role,'permissions' => Permission::getByCategory()]);
    }
    public function update(Request $request){
		$role = Role::find($request->id);
		if (!$role) return view('errors/404');

		$this->validate($request,[
			'name' => 'required|min:3|max:15|alpha_spaces'
		]);
		$role->name = $request->get('name');
		$role->save();
		$permissions = $request->get('permissions');
		if(count($permissions) > 0)
			$role->permissions()->sync($permissions);

		\Session::push('success' , true);
        return redirect('/roles/ver/'.$role->id);
    }
    public function register(Request $request){
		$this->validate($request,[
			'name' => 'required|min:3|max:15|alpha_spaces'
		]);

		$role = new Role();
		$role->name = $request->get('name');
		$role->save();
		$permissions = $request->get('permissions');
		if(count($permissions) > 0)
			$role->permissions()->sync($permissions);

		\Session::push('success' , true);
		return redirect('/roles/ver/'.$role->id);
    }
    public function view(Request $request){
        $role = Role::find($request->id);
        if($role == null){
            return view('errors.404');
        }
        return view('modules.roles.view')->with('role', $role);
    }

	public function  delete(){
        #Hacer
	}
}
