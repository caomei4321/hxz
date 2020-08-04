<?php

namespace App\Http\Controllers\Admin;

use App\Models\DailyProcess;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;

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

    public function export(Request $request, Excel $excel)
    {
        dd($request);
        $firstRow = ['时间', '任务标题', '执行人', '所属单位', '联系方式', '处理地点', '处理描述'];

        $cellData = [
            [11,22,33,44,55,66,77],
            [11,22,33,44,55,66,78]
        ];
        $excel->create('日常任务处理记录导出', function ($excel) use ($cellData, $firstRow) {
            $excel->sheet('first', function ($sheet) use ($cellData, $firstRow) {
                $sheet->prependRow(1, $firstRow);
                $sheet->row($cellData);
            });
        })->export('xls');
    }
}