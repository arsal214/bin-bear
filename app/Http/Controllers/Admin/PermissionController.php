<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\BaseController;
use Yajra\DataTables\Facades\DataTables;
use App\Interfaces\PermissionRepositoryInterface;

class PermissionController extends BaseController
{
    public function __construct(private PermissionRepositoryInterface $permissionRepository)
    {
        $this->middleware('permission:permissions-list', ['only' => ['index']]);
        $this->middleware('permission:permissions-create', ['only' => ['store']]);
        $this->middleware('permission:permissions-edit', ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View | JsonResponse
    {
        if ($request->ajax()) {
            return DataTables::of($this->permissionRepository->webList())
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $canEdit = auth()->user()->can('permissions-edit');
                    $editButton = '';
                    if ($canEdit) {
                        $editButton = "<button class='btn btn-link edit-btn p-0' data-id='{$row->id}'><i class='ph-note-pencil'></i></button>";
                    }
                    return $editButton;

                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.permission.staff.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|unique:permissions,name|regex:/^\S*$/u',
                'display_name' => 'required'
            ]);
            $data = [
                ...$validated,
                'guard_name' => 'web'
            ];
            $this->permissionRepository->storeOrUpdate($data);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return $this->redirectSuccess(route('permissions.shop.index'), 'Permissions created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): RedirectResponse | View
    {
        try {
            $permission = $this->permissionRepository->findById($id);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return view('pages.permission.staff.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'display_name' => 'required'
            ]);
            $data = [
                ...$validated,
                'guard_name' => 'web'
            ];
            $this->permissionRepository->storeOrUpdate($data, $id);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return $this->redirectSuccess(route('permissions.staff.index'), 'Permissions updated successfully.');
    }
}
