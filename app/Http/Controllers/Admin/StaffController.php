<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class StaffController extends BaseController
{
    use UploadTrait;

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RoleRepositoryInterface $roleRepository,
    )
    {
        $this->middleware('permission:users-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:users-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return DataTables::of($this->userRepository->adminList())
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->editColumn('image', function ($row) {
                    $image = "<img src='{$row->image}'  height='50'>";
                    return $image;
                })->addColumn('roles', function ($row) {
                    $badges = '';
                    if (count($row->roles) > 0) {
                        foreach ($row->roles as $role) {
                            $badges .= '<span class="badge bg-primary rounded-pill me-1">' . $role->name . '</span>';
                        }
                    } else {
                        $badges = 'N/A';
                    }
                    return $badges;
                })
                ->addColumn('action', function ($row) {
                    return view('pages.users.admin.actions', compact('row'));
                })
                ->rawColumns(['action', 'image', 'roles'])
                ->make(true);
        }
        return view('pages.users.admin.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|RedirectResponse
    {
        try {
            $roles = $this->roleRepository->webList();
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return view('pages.users.admin.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|same:confirm_password',
                'confirm_password' => 'required|same:password',
                'roles' => 'required'
            ]);

            $data = $request->only(['first_name','last_name', 'password', 'email']);
            $data['is_admin'] = 1;
            $data['type'] = 'Admin';
            $data['image'] = $request->hasFile('image') ? $this->uploadFile($request->file('image'), 'users/staff') : 'https://png.pngtree.com/element_our/20200610/ourmid/pngtree-character-default-avatar-image_2237203.jpg';
            $roles = array_map('intval', $request->roles);
            $this->userRepository->storeOrUpdate($data, $roles);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return $this->redirectSuccess(route('users.staff.index'), 'Staff created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $staff = $this->userRepository->findById($id);
            $roles = $this->roleRepository->webList();
            $staffRole = $staff->roles->pluck('id')->all();
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return view('pages.users.admin.edit', compact('staff', 'roles', 'staffRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'same:confirm_password',
                'roles' => 'required'
            ]);

            $data = $request->only(['first_name','last_name', 'email']);
            if ($request->password) {
                $data['password'] = Hash::make($request->password);
            }
            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadFile($request->file('image'), 'users/staff');
            }
            $data['type'] = 'Admin';
            $roles = array_map('intval', $request->roles);
            $this->userRepository->storeOrUpdate($data, $roles, $id);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return $this->redirectSuccess(route('users.staff.index'), 'Staff updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = $this->userRepository->findById($id);
            if ($user->id == 1 || auth()->user()->id == $user->id) {
                return redirect()->back()->with('warning', 'You cannot delete this staff.');
            }
            $user->delete();
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return $this->redirectSuccess(route('users.staff.index'), 'Staff deleted successfully');
    }
}
