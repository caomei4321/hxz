<?php

namespace App\Http\Controllers\Admin;

use App\Models\CommonTask;
use App\Models\DailyProcess;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CountsController extends Controller
{
    public function index()
    {
        // 总人数
        $userCount = User::all()->count();
        // 当日上报问题数量
        $uploadCount = Event::whereDate('created_at', date('Y-m-d',time()))->count();
        // 当日处理任务数量
        $taskProcessCount = DB::table('user_has_common_tasks')->whereDate('up_at', date('Y-m-d',time()))->count();
        // 当日提交日常任务数
        $uploadEventCount = DailyProcess::whereDate('created_at', date('Y-m-d',time()))->count();

        // 最新日常任务上报
        $dailyTasks = DailyProcess::orderBy('created_at', 'desc')->take(6)->get();

        // 最新临时和专项任务上报
        $commonTasks = DB::table('user_has_common_tasks')->where('up_at', '!=', null)->orderBy('created_at', 'desc')->take(6)->get();

        // 最新发现事件上报
        $uploadEvents = Event::orderBy('created_at', 'desc')->take(6)->get();

        $count = [
            'userCount' => $userCount,
            'uploadCount' => $uploadCount,
            'taskProcessCount' => $taskProcessCount,
            'uploadEventCount' => $uploadEventCount
        ];

        return view('admin.count.index', compact('count', 'dailyTasks', 'commonTasks', 'uploadEvents'));
    }
}
