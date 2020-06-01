<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Handlers\ImageUploadHandler;

class UserController extends Controller
{
    public function completeList()
    {
        $user = Auth()->guard('api')->user();

        // 日常已完成任务
        $dailyCompleteList = $user->dailyProcess()->with('dailyTask')->orderBy('created_at', 'desc')->get();

        // 其他已完成任务
        $commonCompleteList = $user->commonTask()
            ->wherePivot('up_at', '!=', null)
            ->orderBy('created_at', 'desc')
            ->get();

        $completeList = [
            'dailyComplete' => $dailyCompleteList,
            'commonComplete' => $commonCompleteList
        ];

        return $this->message($completeList);
    }

    public function undoneList()
    {
        $user = Auth()->guard('api')->user();

        // 未完成的临时任务
        $undoneList = $user->commonTask()
            ->wherePivot('up_at', null)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $this->message($undoneList);
    }

    // 上报事件
    public function uploadEvent()
    {
        $user = Auth()->guard('api')->user();

        $uploadEvents = $user->event()->orderBy('created_at', 'desc')->paginate(10);

        return $this->message($uploadEvents);
    }
}
