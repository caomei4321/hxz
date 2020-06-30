<?php

namespace App\Http\Controllers\Admin;

use App\Models\CommonTask;
use App\Models\Department;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpecialTasksController extends Controller
{
    public function index(CommonTask $commonTask)
    {
        $commonTasks = $commonTask->where('category', '专项任务')->orderBy('id', 'desc')->paginate(15);
        return view('admin.specialTask.index', compact('commonTasks'));
    }


    public function create(UserCategory $userCategory, Department $department, Request $request)
    {
        $userCategories = $userCategory->all();

        $departments = $department->all();

        $filter = [
            'department_id' => $request->department_id,
            'category_id' => $request->category_id
        ];

        return view('admin.specialTask.add', compact('userCategories', 'departments', 'filter'));
    }

    public function store(Request $request, CommonTask $commonTask)
    {
        $data = $request->only(['title', 'content']);
        $data['status'] = 1;  //刚添加的任务默认状态为进行中
        $data['category'] = '专项任务';
        $commonTask->fill($data);
        $commonTask->save();

        $commonTask->users()->attach($request->users);

        return redirect()->route('admin.specialTask.index');
    }

    public function show(CommonTask $commonTask)
    {
        $commonTasks = $commonTask->users;

        return view('admin.specialTask.show', compact('commonTasks', 'commonTask'));
    }

    public function destroy(CommonTask $commonTask)
    {
        $commonTask->users()->detach();

        $commonTask->delete();

        return response()->json([
            'status'=> 200,
            'message' => '删除成功'
        ]);
    }

    // 修改任务状态
    public function changeStatus(CommonTask $commonTask)
    {
        if ($commonTask->status == 1) {
            $commonTask->status = 0;
        } else {
            $commonTask->status = 1;
        }
        $commonTask->save();

        return response()->json([
            'message' => '修改成功',
            'status' => 200
        ]);
    }
}