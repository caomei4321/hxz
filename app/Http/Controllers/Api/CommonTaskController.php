<?php

namespace App\Http\Controllers\Api;

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
        //                        ->paginate(10);
                                ->get();

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
        //                           ->paginate(10);
                                    ->get();

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

        $user->commonTask()->updateExistingPivot($request->common_id, $data);

        return $this->success([
            'message' => '上报成功'
        ]);
    }
}
