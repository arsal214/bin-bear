<?php

namespace App\Repositories;

use App\Interfaces\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRepository implements PermissionRepositoryInterface
{
    /**
     * Permission for web guard.
     */
    public function list(): Collection
    {
        return Permission::latest()->get();
    }
    /**
     * Permission for web guard.
     */
    public function webList(): Collection
    {
        return Permission::where('guard_name', 'web')->latest()->get();
    }

    /**
     * Permission for api guard.
     */
    public function apiList(): SupportCollection
    {
        $data = DB::table('permissions')->where('guard_name', 'api')
            ->latest();
        return $data->get();
    }
    /**
     * Store permission.
     */
    public function storeOrUpdate(array $data, $id = null): Permission
    {
        $permission = Permission::updateOrCreate(
            ['id' => $id],
            $data
        );

        if ($permission->guard_name == 'web') {
            $role = Role::where('name', 'Super Admin')->first();
            if ($role) {
                $role->givePermissionTo($permission);
            }
        }
        return  $permission;
    }

    /**
     * Find permission by id.
     */
    public function findById($id): Permission
    {
        return Permission::find($id);
    }

    /**
     * Delete permission by id.
     */
    public function destroyById($id): bool
    {
        DB::table('role_has_permissions')->where('permission_id', '=', $id)->delete();
        return Permission::where('id', '=', $id)->delete();
    }
}
