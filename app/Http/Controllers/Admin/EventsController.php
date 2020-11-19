<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EventsExport;
use App\Models\Department;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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

    public function export(Request $request)
    {
        $startTime = $request->start_time ? $request->start_time : date('Y-m-d', time());
        $endTime = $request->end_time ? $request->end_time : date('Y-m-d', strtotime("+1 day"));
        $departmentId =  $request->department_id ? $request->department_id : '';

        return Excel::download(new EventsExport($startTime, $endTime, $departmentId), '上报记录导出.xls');
    }
}
