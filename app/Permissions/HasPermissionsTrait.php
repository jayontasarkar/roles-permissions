<?php 

namespace App\Permissions;

use App\{Role, Permission};

trait HasPermissionsTrait
{

	public function givePermissionTo(...$permission)
	{
		$permissions = $this->getAllPermissions(array_flatten($permission));

		if($permissions == null || ! count($permissions)) {
			return $this;
		}
		$this->permissions()->saveMany($permissions);

		return $this;
	}

	protected function getAllPermissions(array $permission)
	{
		return Permission::whereIn('name', $permission)->get();
	}
	/**
	 * check if user has permission
	 * @param  [type]  $permission [description]
	 * @return boolean        [description]
	 */
	public function hasPermissionTo($permission)
	{
		return $this->hasPermissionThroughRole($permission) || 
			    $this->hasPermission($permission);
	}

	protected function hasPermissionThroughRole($permission)
	{
		foreach($permission->roles as $role){
			if ( $this->roles->contains('name', $role) ) {
				return true;
			}
		}

		return false;
	}

	protected function hasPermission($permission)
	{
		return (bool) $this->permissions->where('name', $permission->name)->count();
	}

	/**
	 * check if user has roles
	 * @param  [type]  $roles [description]
	 * @return boolean        [description]
	 */
	public function hasRole(...$roles)
	{
		foreach($roles as $role){
			if ( $this->roles->contains('name', $role) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * user belongs to many roles
	 * 
	 * @return [type] [description]
	 */
	public function roles()
	{
		return $this->belongsToMany(Role::class, 'users_roles');
	}

	/**
	 * user belongs to many permissions
	 * 
	 * @return [type] [description]
	 */
	public function permissions()
	{
		return $this->belongsToMany(Permission::class, 'users_permissions');
	}
}