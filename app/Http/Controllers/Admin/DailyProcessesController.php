<?php

namespace App\Http\Controllers\Admin;

use App\Models\DailyProcess;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Exports\DailyProcessesExport;
use Maatwebsite\Excel\Facades\Excel;

class DailyProcessesController extends Controller
{
    public function index(DailyProcess $dailyProcess, Department $department, Request $request)
    {
        $startTime = $request->start_time ? $request->start_time : date('Y-m-d', time());
        $endTime = $request->end_time ? $request->end_time : date('Y-m-d', strtotime("+1 day"));
        $departmentId =  $request->department_id ? $request->department_id : '';

        if ($departmentId) {
            $users = DB::table('users')->where('department_id', $departmentId)->pluck('id');
            $dailyProcesses = $dailyProcess->whereBetween('created_at', [$startTime,$endTime])
                                    ->whereIn('user_id',$users)->orderBy('id', 'desc')->paginate();
        } else {
            $dailyProcesses = $dailyProcess->whereBetween('created_at', [$startTime,$endTime])
                                    ->orderBy('id', 'desc')->paginate();
        }

        $departments = $department->all();
        $filter = [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'department_id' => $departmentId
        ];
        return view('admin.dailyProcess.index', compact('dailyProcesses', 'departments', 'filter'));
    }

    public function show(DailyProcess $dailyProcess)
    {
        return view('admin.dailyProcess.show', compact('dailyProcess'));
    }

    public function destroy(DailyProcess $dailyProcess)
    {
        $dailyProcess->delete();

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

        return Excel::download(new DailyProcessesExport($startTime, $endTime, $departmentId), '日常任务处理记录导出.xls');
    }
}