<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\User;
use Auth;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:users-list',  ['only' => ['index']]);
        $this->middleware('permission:users-view',  ['only' => ['show']]);
        $this->middleware('permission:users-create',['only' => ['create','store']]);
        $this->middleware('permission:users-edit',  ['only' => ['edit','update']]);
        $this->middleware('permission:users-delete',['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::AcceptRequest(['name', 'status', 'name_like', 'email'])
            ->filter()
            ->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        $roles = Role::pluck('name','id')->all();

        return view('admin.users.create',compact('roles','user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'              => 'required',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|same:confirm_password',
            'confirm_password'  => 'required|same:password',
            'roles'             => 'required'
        ]);

        $user = User::create($request->all());
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','id')->all();
        $userRole = $user->roles->pluck('name','id')->all();

        return view('admin.users.edit',compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'same:confirm-password',
            'roles'     => 'required'
        ]);

        $input = $request->all();
        if (empty($input['password'])) {
            $input = Arr::except($input, array('password'));
        }

        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $user->id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user->id == 1 || auth()->user()->id ==  $id) {
            return redirect()->back()->with('warning','You cannot delete this user.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success','User deleted successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profileEdit()
    {
        return view('admin.users.profile');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'name'              => 'required',
            'email'             => 'required|email|unique:users,email,' . auth()->user()->id,
            'old_password'      => 'nullable|required_with:new_password',
            'new_password'      => 'nullable|min:8|max:12',
            'confirm_password'  => 'nullable|min:8|max:12|required_with:new_password|same:new_password'
        ]);

        $input = $request->all();

        if (empty($input['new_password'])) {
            $input = Arr::except($input, array('password'));
        }
        auth()->user()->update($input);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    /**
     * Validate a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkEmail(Request $request)
    {
        if ($request->id) {
            $user = User::where('id','!=',$request->id)->where('email', $request->email)->first();
        }else{
            $user = User::where('email', $request->email)->first();
        }

        if($user){ echo "false"; }else{ echo "true";}
    }

    /**
     * Validate a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkPassword(Request $request)
    {
        $user = User::find($request->id);
        if(!Hash::check($request->old_password, $user->password)) { echo "false"; }else{ echo "true";}
    }
}
