<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id', 'name', 'slug', 'level',
	];
	public function permissions(){
		return $this->belongsToMany('App\Permission', 'roles_has_permissions', 'role_id', 'permission_id');
	}
	public function users(){

	}
}
