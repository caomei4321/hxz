<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\Curl;
use App\Models\Coordinate;
use App\Models\Department;
use App\Models\User;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Excel;

class UsersController extends Controller
{
    public function index(User $user, Department $department, Request $request)
    {
        if ($request->name || $request->phone || $request->department_id) {
            $builder = User::query();
            if ($request->name) {
                $name = '%'.$request->name.'%';
                $builder = $builder->where('name', 'like', $name);
            }
            if ($request->phone) {
                $builder = $builder->where('phone', $request->phone);
            }
            if ($request->department_id) {
                $builder = $builder->where('department_id', $request->department_id);
            }
            $users = $builder->paginate(15);;
        } else {
            $users = $user->paginate(15);
        }
        $filter = [
            'name' => $request->name,
            'phone' => $request->phone,
            'department_id' => $request->department_id
        ];

        $departments = $department->all();

        return view('admin.user.index', compact('users', 'departments', 'filter'));
    }

    public function create(User $user, UserCategory $userCategory, Department $department)
    {
        $categories = $userCategory->all();
        $departments = $department->all();
        return view('admin.user.create_and_edit', compact('user', 'categories', 'departments'));
    }

    public function store(Request $request, User $user)
    {
        $data = $request->only(['name', 'phone', 'password', 'age',  'category_id', 'department_id']);
        $data['password'] = Hash::make($data['password']);
        $user->fill($data);
        $user->save();

        return response()->json([
            'status'=> 200,
            'message' => '添加成功'
        ]);
    }

    public function edit(User $user, UserCategory $userCategory, Department $department)
    {
        $categories = $userCategory->all();
        $departments = $department->all();
        return view('admin.user.create_and_edit', compact('user', 'categories', 'departments'));
    }

    public function update(Request $request, User $user)
    {

        $data = $request->only(['name', 'phone', 'password', 'age',  'category_id', 'department_id']);
        if ($user->password != $data['password']) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);

        return response()->json([
            'status' => 200,
            'message' => '更新成功'
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->event()->count()>0 || $user->dailyTask()->count()>0 || $user->commonTask()->count()>0) {
            return response()->json([
                'status' => 400,
                'message' => '请删除当前用户下的所有数据'
            ]);
        }
        $user->delete();

        return response()->json([
            'status'=> 200,
            'message' => '删除成功'
        ]);
    }
}
