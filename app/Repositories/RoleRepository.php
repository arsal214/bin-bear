<?php

namespace App\Repositories;

use App\Interfaces\RoleRepositoryInterface;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository implements RoleRepositoryInterface
{
    /**
     * Roles List.
     */
    public function list(): Collection
    {
        return Role::get();
    }

    /**
     * Web Roles List.
     */
    public function webList(): Collection
    {
        return Role::where('guard_name', 'web')->whereNot('name', 'Super Admin')->get();
    }

    /**
     * API Roles List.
     */
    public function apiList(): Collection
    {
        return Role::where('guard_name', 'api')->get();
    }

    /**
     * Store or Update role.
     */
    public function storeOrUpdate($name, $permissions, $id = null, $guard = 'web')
    {
        $role = Role::updateOrCreate(
            ['id' => $id],
            ['name' => $name, 'guard_name' => $guard]
        );
        $role->syncPermissions($permissions);
        return $role;
    }


    /**
     * FInd role by id.
     */
    public function findById($id)
    {
        return Role::find($id);
    }
}
