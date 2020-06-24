<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Handlers\ImageUploadHandler;

class MessageController extends Controller
{
    public function messageList()
    {
        $user = Auth()->guard('api')->user();

        $messages = $user->message()->orderBy('created_at', 'desc')->paginate();
        //$messages = $user->message()->orderBy('created_at', 'desc')->get();

        //$dailyTaskList = $user->dailyTask()->orderBy('created_at', 'desc')->paginate(10);

        return $this->message($messages);
    }

    public function readMessage(Request $request)
    {
        $user = Auth()->guard('api')->user();

        $user->message()->updateExistingPivot($request->id, ['status' => 1]);

        return $this->success('更新成功');
    }
}
