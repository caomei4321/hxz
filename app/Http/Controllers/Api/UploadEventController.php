<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Handlers\ImageUploadHandler;

class UploadEventController extends Controller
{
    // 用户上报事件
    public function eventStore(Request $request, ImageUploadHandler $uploadHandler)
    {
        $user = Auth()->guard('api')->user();

        //$res = $uploadHandler->save($request->file('img'),'uploadEvent','event');

        $user->event()->create([
            'category' => $request->category,
            'img' => json_encode($request->img),
            'address' => $request->address,
            'description' => $request->description,
            'status' => $request->status
        ]);

        return $this->success([
            'message' => '上报成功'
        ]);
    }
}
