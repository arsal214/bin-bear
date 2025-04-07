<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Interfaces\PermissionRepositoryInterface;
use App\Interfaces\RoleRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends BaseController
{
    public function __construct(
        private RoleRepositoryInterface $roleRepository,
        private PermissionRepositoryInterface $permissionRepository
    ) {
        $this->middleware('permission:roles-list', ['only' => ['index']]);
        $this->middleware('permission:roles-create', ['only' => ['store']]);
        $this->middleware('permission:roles-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles-delete', ['only' => ['destroy']]);

    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('pages.roles.staff.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $data = $this->roleRepository->webList();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return view('pages.roles.staff.actions', [
                    'id' => $row->id
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create(): View | RedirectResponse
    {
        try {
            $permissionGroup = $this->createPermissionGroup($this->permissionRepository->webList());
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return view('pages.roles.staff.create', compact('permissionGroup'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $this->validate($request, [
                'name'       => 'required|unique:roles,name',
                'permission' => 'required',
            ]);
            $permissions =  array_map('intval', $request->permission);
            $this->roleRepository->storeOrUpdate($request->name, $permissions);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return $this->redirectSuccess(route('roles.staff.index'), 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): RedirectResponse | View
    {
        try {
            $role = $this->roleRepository->findById($id);
            $permissionGroup = $this->createPermissionGroup($this->permissionRepository->webList(), $role);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return view('pages.roles.staff.show', compact('role', 'permissionGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): RedirectResponse | View
    {
        try {
            $role = $this->roleRepository->findById($id);
            $permissionGroup = $this->createPermissionGroup($this->permissionRepository->webList(), $role);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return view('pages.roles.staff.edit', compact('role', 'permissionGroup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {

        try {
            $this->validate($request, [
                'name'      => 'required',
                'permission' => 'required',
            ]);
            $permissions =  array_map('intval', $request->permission);
            $this->roleRepository->storeOrUpdate($request->name, $permissions, $id);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return $this->redirectSuccess(route('roles.staff.index'), 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $role = $this->roleRepository->findById($id);
            if (count($this->permissionRepository->webList()) == 1 && $role->id == 1) {
                return redirect()->back()->with('warning', 'You cannot delete the last role.');
            }
            $role->delete();
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return  $this->redirectSuccess(route('roles.staff.index'), 'Role deleted successfully');
    }


    private function createPermissionGroup($permissions, $role = null): array
    {
        $permissionGroup = [];
        foreach ($permissions as $permission) {
            if ($role) {
                in_array($permission->name, $role->permissions->pluck('name')->toArray()) ? $exist = 'checked' : $exist = '';
            }
            $title = explode('-', $permission->name);
            $permissionGroup[$title[0]][] = array(
                'id' => $permission->id, 'name' => $title[1],
                'exist' => $exist ?? ''
            );
        }
        return $permissionGroup;
    }
}
