<?php

namespace App\Http\Controllers\Admin;

use App\Models\CommonTask;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpecialTasksController extends Controller
{
    public function index(CommonTask $commonTask)
    {
        $commonTasks = $commonTask->where('category', '专项任务')->paginate(15);
        return view('admin.specialTask.index', compact('commonTasks'));
    }


    public function create(UserCategory $userCategory)
    {
        $userCategories = $userCategory->all();

        return view('admin.specialTask.add', compact('userCategories'));
    }

    public function store(Request $request, CommonTask $commonTask)
    {
        $data = $request->only(['title', 'content']);
        $data['status'] = 1;  //刚添加的任务默认状态为进行中
        $data['category'] = '专项任务';
        $commonTask->fill($data);
        $commonTask->save();

        $commonTask->users()->attach($request->users);

        return response()->json([
            'status'=> 200,
            'message' => '添加成功'
        ]);
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
}