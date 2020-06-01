<?php

namespace App\Http\Controllers\Admin;

use App\Models\DailyTask;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

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

    public function show(DailyTask $dailyTask)
    {
        $dailyTasks = $dailyTask->dailyProcess()->groupBy('user_id')->get();
       /* foreach ($dailyTasks as $daily) {
            dd($daily->user->department->name);
        }*/
//dd($dailyTasks);
        return view('admin.dailyTask.show', compact('dailyTask','dailyTasks'));
    }

    public function destroy(DailyTask $dailyTask)
    {
        $dailyTask->users()->detach();
        $dailyTask->dailyProcess()->delete();

        $dailyTask->delete();

        return response()->json([
            'status'=> 200,
            'message' => '删除成功'
        ]);
    }

    public function showUserList(User $user, DailyTask $dailyTask)
    {
        $dailyTasks = $user->dailyProcess()->where('daily_id', $dailyTask->id)->orderBy('created_at', 'desc')->get();

        return view('admin.dailyTask.showUserList', compact('dailyTasks', 'dailyTask'));

    }
}