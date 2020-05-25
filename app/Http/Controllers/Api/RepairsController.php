<?php

namespace App\Http\Controllers\Api;

use App\Models\Repair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RepairsController extends Controller
{
    public function thisUser(Request  $request)
    {

        return Auth::guard('api')->user();
    }

    public function count()
    {
        $user = Auth::guard('api')->user();

        // 未完结日常任务
        $dailyTaskCount = $user->dailyTask()->where('status', 1)->count();

        // 未完成专项任务
        $specialTaskCount = $user->commonTask()->where([
                                    ['status', 1],
                                    ['category', '专项任务']])
                                ->wherePivot('up_at',null)
                                ->count();
        // 未完成临时任务
        $temporaryTaskCount = $user->commonTask()->where([
                                        ['status', 1],
                                        ['category', '临时任务']])
                                    ->wherePivot('up_at',null)
                                    ->count();

        $messageCount = $user->message()->wherePivot('status',0)->count();

        return $this->message([
            'dailyTaskCount' => $dailyTaskCount,
            'specialTaskCount' => $specialTaskCount,
            'temporaryTaskCount' => $temporaryTaskCount,
            'messageCount' => $messageCount
        ]);
    }

    public function repairs(Repair $repair)
    {
        $repairs = $repair->where('status',0)->paginate(7);
        return  $repairs;
    }

    public function eventReport(Request $request)
    {
        $path = Storage::disk('public')->putfile('badImg',$request->file('bad_img'));
        $url = Storage::url($path);

        $request->user()->repairs()->create([
            'address' => $request->address,
            'description' => $request->description,
            'bad_img'   => $url
        ]);

        return $this->response->created('',['msg' => '添加成功']);
    }

    public function completeRepair(Request $request, Repair $repair)
    {
        $path = Storage::disk('public')->putfile('goodImg', $request->file('good_img'));
        $url = Storage::url($path);

        $repair = $repair->find($request->id);

        $repair->update([
            'status' => 1,
            'good_img' => $url
        ]);

        return $this->response()->accepted('',['msg' => '维修完成']);

    }

    public function repairDetail(Request $request, Repair $repair)
    {
        $repair = $repair->find($request->id);

        return $repair;
    }
}
