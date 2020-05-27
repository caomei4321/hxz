<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentsController extends Controller
{
    public function index(Department $department)
    {
        $departments = $department->paginate(15);
        return view('admin.department.index', compact('departments'));
    }

    public function create(Department $department)
    {
        return view('admin.department.create_and_edit', compact('department'));
    }

    public function store(Request $request, Department $department)
    {
        $department->fill($request->only(['name']));
        $department->save();

        return response()->json([
            'status'=> 200,
            'message' => '添加成功'
        ]);
    }

    public function edit(Department $department)
    {
        return view('admin.department.create_and_edit', compact('department'));
    }

    public function update(Department $department, Request $request)
    {
        $department->update($request->only(['name']));
        return response()->json([
            'status' => 200,
            'message' => '更新成功'
        ]);
    }

    public function destroy(Department $department)
    {
        if ($department->users()->count() > 0) {
            return response()->json([
                'status' => 400,
                'message' => '请先删除当前部门下的用户'
            ]);
        }
        $department->delete();
        return response()->json([
            'status' => 200,
            'message' => '删除成功'
        ]);
    }
}
