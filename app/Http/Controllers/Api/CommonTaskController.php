<?php

namespace App\Http\Controllers\Api;

use App\Models\CommonTask;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Handlers\ImageUploadHandler;

class CommonTaskController extends Controller
{
    public function specialTasks()
    {
        $user = Auth()->guard('api')->user();

        // 未完成的专项任务
        $specialTaskList = $user->commonTask()
                                ->where([
                                    ['status', 1],
                                    ['category', '专项任务']
                                ])
                                ->wherePivot('up_at',null)
                                ->orderBy('created_at', 'desc')
                                ->paginate(20);
        //                        ->get();

        return $this->message($specialTaskList);
    }

    public function temporaryTasks()
    {
        $user = Auth()->guard('api')->user();

        // 未完成的临时任务
        $temporaryTaskList = $user->commonTask()
                                    ->where([
                                        ['status', 1],
                                        ['category', '临时任务']
                                    ])
                                    ->wherePivot('up_at',null)
                                    ->orderBy('created_at', 'desc')
                                   ->paginate(20);
        //                            ->get();

        return $this->message($temporaryTaskList);
    }

    // 用户处理任务的详情
    public function store(Request $request, ImageUploadHandler $uploadHandler)
    {
        $user = Auth()->guard('api')->user();

        $data = [
            'address' =>$request->address,
            'description' => $request->description,
            'photo' => json_encode($request->img),
            'up_at' => date('Y-m-d H:i:s',time())
        ];

        $status = $user->commonTask()->updateExistingPivot($request->common_id, $data);

        if ($status) {
            $commonTask = CommonTask::find($request->common_id);
            // 未完成的任务的数量
            $count = $commonTask->users()->wherePivot('up_at', '=', null)->count();

            // 所有人都完成任务后修改任务状态为已完结
            if ($count == 0) {
                $commonTask->status = 0;
                $commonTask->save();
            }
        }

        return $this->success([
            'message' => '上报成功'
        ]);
    }
}
