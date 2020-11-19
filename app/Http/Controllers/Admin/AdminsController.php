<?php

namespace App\Http\Controllers\Admin;

//use App\Models\Station;
//use App\Observers\AdministratorObservers;
use App\Models\Admin;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use App\Http\Requests\AdministratorRequest;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminsController extends Controller
{
    public function index(Admin $administrator)
    {
        $administrators = $administrator->all();
        return view('admin.admin.index', compact('administrators'));
    }

    public function create(Admin $administrator, Role $role, Department $department)
    {
        $roles = $role->all();
        $departments = $department->all();
        //dd($stations);
        return view('admin.admin.create_and_edit', compact('administrator', 'roles', 'departments'));
    }

    public function store(Request $request, Admin $administrator)
    {
        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'department_id' => $request->department_id,
        ];
        $administrator = $administrator->create($data);

        $administrator->syncRoles($request->administrator_roles);
        return redirect()->route('admin.administrators.index');
    }


    public function show(Admin $administrator)
    {
        //dd($administrator->getRoleNames());
        //return view('admin.administrators.show', compact('administrator'));
    }


    public function edit(Admin $administrator, Role $role, Department $department)
    {
        $roles = $role->all();
        $administrator_roles = $administrator->getRoleNames()->toArray();
        $departments = $department->all();
        //dd($administrator_roles);
        return view('admin.admin.create_and_edit', compact('administrator', 'roles', 'administrator_roles', 'departments'));
    }


    public function update(Request $request, Admin $administrator)
    {
        if ($administrator->password == $request->password) {
        //if (Hash::check($request->password,$administrator->password)) {
            $administrator->update($request->only(['name', 'phone', 'department_id']));
        } else {
            $administrator->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'department_id' => $request->department_id,
                'password' => Hash::make($request->password)
            ]);
        }
        $administrator->syncRoles($request->administrator_roles);
        return redirect()->route('admin.administrators.index');
    }

    public function destroy(Admin $administrator)
    {
        $administrator->delete();
        return response()->json(['status' => 1, 'msg' => '删除成功']);
    }
}
