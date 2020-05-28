<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Handlers\ImageUploadHandler;

class DailyTaskController extends Controller
{
    public function index()
    {
        $user = Auth()->guard('api')->user();

        $dailyTaskList = $user->dailyTask()->where('status', 1)->orderBy('created_at', 'desc')->paginate(10);

        return $this->message($dailyTaskList);
    }

    // 用户处理任务的详情
    public function store(Request $request, ImageUploadHandler $uploadHandler)
    {
        $user = Auth()->guard('api')->user();

        $res = $uploadHandler->save($request->file('img'),'dailyTask','daily');

        $dailyProcess = $user->dailyProcess()->create([
                                            'daily_id' => $request->daily_id,
                                            'address' => $request->address,
                                            'description' => $request->description,
                                            'photo' => $res['path']
                                        ]);
        //$dailyProcess->save();

        return $this->success([
            'message' => '上报成功'
        ]);
    }
}
