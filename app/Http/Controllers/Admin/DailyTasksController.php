<?php

namespace App\Http\Controllers\Admin;

use App\Models\DailyTask;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DailyTasksController extends Controller
{
    public function index(DailyTask $dailyTask)
    {
        $dailyTasks = $dailyTask->paginate(15);
        return view('admin.dailyTask.index', compact('dailyTasks'));
    }


    public function create(UserCategory $userCategory)
    {
        $userCategories = $userCategory->all();

        return view('admin.dailyTask.add', compact('userCategories'));
    }

    public function store(Request $request, DailyTask $dailyTask)
    {
        $data = $request->only(['title', 'content']);
        $data['status'] = 1;  //刚添加的任务默认状态为进行中
        $dailyTask->fill($data);
        $dailyTask->save();

        $dailyTask->users()->attach($request->users);

        return response()->json([
            'status'=> 200,
            'message' => '添加成功'
        ]);
    }

    public function show()
    {

    }

    public function destroy(DailyTask $dailyTask)
    {
        $dailyTask->users()->detach();

        $dailyTask->delete();

        return response()->json([
            'status'=> 200,
            'message' => '删除成功'
        ]);
    }
}