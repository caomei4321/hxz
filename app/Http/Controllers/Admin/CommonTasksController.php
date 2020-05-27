<?php

namespace App\Http\Controllers\Admin;

use App\Models\CommonTask;
use App\Models\DailyTask;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommonTasksController extends Controller
{
    public function index(CommonTask $commonTask)
    {
        $commonTasks = $commonTask->paginate(15);
        return view('admin.commonTask.index', compact('commonTasks'));
    }


    public function create(UserCategory $userCategory)
    {
        $userCategories = $userCategory->all();

        return view('admin.commonTask.add', compact('userCategories'));
    }

    public function store(Request $request, CommonTask $commonTask)
    {
        $data = $request->only(['title', 'content', 'category']);
        $data['status'] = 1;  //刚添加的任务默认状态为进行中
        $commonTask->fill($data);
        $commonTask->save();

        $commonTask->users()->attach($request->users);

        return response()->json([
            'status'=> 200,
            'message' => '添加成功'
        ]);
    }

    public function show()
    {

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