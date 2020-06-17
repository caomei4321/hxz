<?php

namespace App\Http\Controllers\Api;

use App\Models\Sign;
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

        return $this->message($user);
    }
}
