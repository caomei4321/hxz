<?php

namespace App\Http\Controllers\Admin;

use App\Models\CommonProcess;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;

class TemporaryProcessesController extends Controller
{
    public function index(CommonProcess $commonTask, Department $department, Request $request)
    {
        $startTime = $request->start_time ? $request->start_time : date('Y-m-d', time());
        $endTime = $request->end_time ? $request->end_time : date('Y-m-d', strtotime("+1 day"));
        $departmentId =  $request->department_id ? $request->department_id : '';
        //$category = $request->category ? $request->category : '';

        if ($departmentId) {
            $users = DB::table('users')->where('department_id', $departmentId)->pluck('id');
            $commonProcesses =$commonTask->whereBetween('up_at',[$startTime, $endTime])->whereIn('user_id', $users);
        } else {
            $commonProcesses =$commonTask->whereBetween('up_at',[$startTime, $endTime]);
        }
        //if ($category) {
        $categories = DB::table('common_tasks')->where('category', '临时任务')->pluck('id');
        $commonProcesses = $commonProcesses->whereIn('common_id', $categories)->orderBy('id', 'desc')->paginate();
        //} else {
        //    $commonProcesses = $commonProcesses->paginate();
        //}

        $departments = $department->all();
        $filter = [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'department_id' => $departmentId,
            //'category' => $category
        ];
        return view('admin.temporaryProcess.index', compact('commonProcesses', 'departments', 'filter'));
    }

    public function show(CommonProcess $commonProcess)
    {
        return view('admin.temporaryProcess.show', compact('commonProcess'));
    }

    public function destroy(CommonProcess $commonProcess)
    {
        $commonProcess->delete();

        return response()->json([
            'status'=> 200,
            'message' => '删除成功'
        ]);
    }

    public function export(Request $request, Excel $excel, CommonProcess $commonProcess)
    {
        $startTime = $request->start_time ? $request->start_time : date('Y-m-d', time());
        $endTime = $request->end_time ? $request->end_time : date('Y-m-d', strtotime("+1 day"));
        $departmentId =  $request->department_id ? $request->department_id : '';

        if ($departmentId) {
            $users = DB::table('users')->where('department_id', $departmentId)->pluck('id');
            $commonProcesses =$commonProcess->whereBetween('up_at',[$startTime, $endTime])->whereIn('user_id', $users);
        } else {
            $commonProcesses =$commonProcess->whereBetween('up_at',[$startTime, $endTime]);
        }

        $categories = DB::table('common_tasks')->where('category', '临时任务')->pluck('id');
        $commonProcesses = $commonProcesses->whereIn('common_id', $categories)->orderBy('created_at', 'asc')->get();

        $cellData = [];

        $firstRow = ['时间', '任务标题', '执行人', '所属单位', '联系方式', '处理地点', '处理描述'];

        foreach ($commonProcesses as $value) {
            $createdAt = $value->up_at;
            $title = $value->commonTask->title;
            $userName = $value->user->name;
            $departmentName = $value->user->department->name;
            $userTel = $value->user->phone;
            $address = $value->address;
            $description = $value->description;

            $data = [
                $createdAt, $title, $userName, $departmentName, $userTel, $address, $description
            ];
            array_push($cellData, $data);
        }

        $excel->create('临时任务处理记录导出', function ($excel) use ($cellData, $firstRow) {
            $excel->sheet('first', function ($sheet) use ($cellData, $firstRow) {
                $sheet->prependRow(1, $firstRow);
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}