<?php

namespace App\Http\Controllers\Api;

use App\Models\Sign;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SignController extends Controller
{
    // 用户签到
    public function sign(Request $request, Sign $sign)
    {
        $user = Auth()->guard('api')->user();
        $user->sign()->create([
            'type' => $request->type
        ]);
        // 更改用户当前状态
       $user->sign_type = $request->type;
       $user->save();

       return $this->success([
           'message' => '签到成功'
       ]);
    }
    // 获取用户当前状态
    public function userStatus()
    {
        $user = Auth()->guard('api')->user();

        if ($user->sign_type == 1) {
            $signIn = Sign::where([
                ['user_id', $user->id],
                ['type', 1]
            ])->orderBy('created_at', 'desc')->limit(1)->get();
            $user['sign_in_time'] = $signIn[0]->created_at->toDateTimeString();
            $user['sign_in_msg'] = '打卡成功';
            $user['sign_out_time'] = '';
            $user['sign_out_msg'] = '暂无打卡';
        } else {
            $user['sign_in_time'] = '';
            $user['sign_in_msg'] = '暂无打卡';
            $user['sign_out_time'] = '';
            $user['sign_out_msg'] = '暂无打卡';
        }

        return $this->message($user);
    }
}
