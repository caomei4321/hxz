<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SendMessage;
use App\Models\CommonTask;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TemporaryTasksController extends Controller
{
    public function index(CommonTask $commonTask)
    {
        $commonTasks = $commonTask->where('category', '临时任务')->paginate(15);
        return view('admin.temporaryTask.index', compact('commonTasks'));
    }


    public function create(UserCategory $userCategory)
    {
        $userCategories = $userCategory->all();

        return view('admin.temporaryTask.add', compact('userCategories'));
    }

    public function store(Request $request, CommonTask $commonTask)
    {
        $data = $request->only(['title', 'content']);
        $data['status'] = 1;  //刚添加的任务默认状态为进行中
        $data['category'] = '临时任务';
        $commonTask->fill($data);
        $commonTask->save();

        $commonTask->users()->attach($request->users);

        foreach ($request->users as $key => $value) {
            $job = new SendMessage($value, '0-wReXMBf0gg7Br3HRaZ-lW5x55hu5ot_d5k3YncJgc', 'pages/basics/temporary?id='.$commonTask->id, $data);
            dispatch($job);
        }

        return response()->json([
            'status'=> 200,
            'message' => '添加成功'
        ]);
    }

    public function show(CommonTask $commonTask)
    {
        $commonTasks = $commonTask->users;

        return view('admin.temporaryTask.show', compact('commonTasks', 'commonTask'));
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