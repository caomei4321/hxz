<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EventsExport;
use App\Models\Department;
use App\Models\Event;
use App\Models\EventReply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;

class EventsController extends Controller
{
    /*public function __construct(Request $request)
    {

        $action = $request->route()->getAction();
        $controller = '@';
        if (isset($action['controller'])) {
            $controller = class_basename($action['controller']);
            $controller = explode('@', $controller);
        }
        $controller = $controller[1]; // 获取请求的方法
        dd($request->user());
    }*/

    public function index(Request $request, Event $event, Department $department)
    {
        if (Auth::user()->department_id) {
            $departmentId = Auth::user()->department_id;

        } else {
            $departmentId =  $request->department_id ? $request->department_id : '';
        }
        $startTime = $request->start_time ? $request->start_time : date('Y-m-d', time());
        $endTime = $request->end_time ? $request->end_time : date('Y-m-d', strtotime("+1 day"));
        //$departmentId =  $request->department_id ? $request->department_id : '';

        if ($departmentId) {
            $users = DB::table('users')->where('department_id', $departmentId)->pluck('id');
            $events = $event->whereBetween('created_at', [$startTime,$endTime])
                ->whereIn('user_id',$users);
        } else {
            $events = $event->whereBetween('created_at', [$startTime,$endTime]);
        }

        $events = $events->with(['replies' => function($query) {
                                    $query->orderBy('created_at', 'desc');
                                }, 'adminReplies' => function($query) {
                                    $query->orderBy('created_at', 'desc');
                                }])->orderBy('id', 'desc')->paginate(15);

        $departments = $department->all();
        $filter = [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'department_id' => $departmentId
        ];

        return view('admin.event.index', compact('events', 'departments', 'filter'));
    }

    public function show(Event $event)
    {
        return view('admin.event.show', compact('event'));
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json([
            'status'=> 200,
            'message' => '删除成功'
        ]);
    }

    public function export(Request $request, Excel $excel)
    {
        $startTime = $request->start_time ? $request->start_time : date('Y-m-d', time());
        $endTime = $request->end_time ? $request->end_time : date('Y-m-d', strtotime("+1 day"));
        $departmentId =  $request->department_id ? $request->department_id : '';

        new EventsExport($startTime, $endTime, $departmentId, $excel);
    }

    public function reply(Request $request, Event $event, EventReply $eventReply)
    {
        if (count($event->replies()->get()) == 0) {
            $event->adminReplies()->create([
                'reply' => $request->reply
            ]);
        } else {
            $lastReply = $eventReply::where('event_id', $event->id)->orderBy('id', 'desc')->limit(1)->get();
            $event->adminReplies()->create([
                'event_reply_id' => $lastReply[0]->id,
                'reply' => $request->reply
            ]);
        }
        return response()->json([
            'status'=> 200,
            'message' => '添加成功'
        ]);
    }

    public function eventCount() {
        return Event::query()->count();
    }
}
