<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Models\EventReply;
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

    // 上报的事件详情
    public function eventDetail(Request $request)
    {
        $eventId = $request->id;

        $eventDetail = Event::where('id', $eventId)->with(['adminReplies' => function ($query) {
                                                $query->where('event_reply_id', null);
                                            },'replies' => function ($query) {
                                                $query->with('adminReplies');
                                            }])->get();

        return $this->message($eventDetail);
    }

    // 添加上报事件回复
    public function appendReply(Request $request, EventReply $eventReply)
    {
        Event::where('id', $request->event_id)->update(['status' => 2]);

        $data = [
            'event_id' => $request->event_id,
            'reply' => $request->reply,
            'img' => json_encode($request->img)
        ];
        $eventReply->fill($data);
        $eventReply->save();

        return $this->success([
            'message' => '添加成功',
            'reply' => $eventReply
        ]);
    }
}
