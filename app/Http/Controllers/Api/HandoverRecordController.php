<?php

namespace App\Http\Controllers\Api;

use App\Jobs\SendMessage;
use App\Models\HandoverRecord;
use App\Models\User;
use Illuminate\Http\Request;

class HandoverRecordController extends Controller
{
    // 我的交接
    public function records()
    {
        $user = Auth()->guard('api')->user();
        $records = $user->handoverRecipient()->whereDate('created_at', '>=', date('Y-m-d', strtotime("-1 day")))
                                            ->whereDate('created_at', '<=', date('Y-m-d', time()))
                                            ->with('sendUser')
                                            ->orderBy('created_at', 'desc')->get();

       return $this->message($records);
    }
    // 获取可交接用户
    public function getUsers(User $user)
    {
        $thisUser = Auth()->guard('api')->user();
        $users = $user->where('department_id', $thisUser->department_id)->get();

        return $this->message($users);
    }
    // 保存交接信息
    public function storeHandover(Request $request)
    {
        $user = Auth()->guard('api')->user();
        $user->handoverSender()->create([
            'recipient_user' => $request->recipient_user,
            'content' => $request->description
        ]);
        $job = new SendMessage($request->recipient_user, '0-wReXMBf0gg7Br3HRaZ-lW5x55hu5ot_d5k3YncJgc', 'pages/basics/shift', [
            'title' => '交接信息',
            'content' => $request->description
        ]);
        dispatch($job);

        return $this->success([
            'message' => '交接成功'
        ]);
    }
    // 设置交接信息为已读
    public function readHandover(Request $request)
    {
        $user = Auth()->guard('api')->user();

        $user->handoverRecipient()->where('id', $request->id)->update([
            'read_time' => date('Y-m-d H:i:s', time())
        ]);

        return $this->success([
            'message' => '修改成功',
            'read_time' => date('Y-m-d H:i:s', time())
        ]);
    }
}
