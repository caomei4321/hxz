<?php

namespace App\Http\Controllers\Admin;

use App\Models\CommonProcess;
use App\Models\CommonTask;
use App\Models\Department;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CommonProcessesController extends Controller
{
    public function index(CommonProcess $commonTask, Department $department, Request $request)
    {
        $startTime = $request->start_time ? $request->start_time : date('Y-m-d', time());
        $endTime = $request->end_time ? $request->end_time : date('Y-m-d', strtotime("+1 day"));
        $departmentId =  $request->department_id ? $request->department_id : '';
        $category = $request->category ? $request->category : '';

        if ($departmentId) {
            $users = DB::table('users')->where('department_id', $departmentId)->pluck('id');
            $commonProcesses =$commonTask->whereBetween('up_at',[$startTime, $endTime])->whereIn('user_id', $users);
        } else {
            $commonProcesses =$commonTask->whereBetween('up_at',[$startTime, $endTime]);
        }
        if ($category) {
            $categories = DB::table('common_tasks')->where('category',$category)->pluck('id');
            $commonProcesses = $commonProcesses->whereIn('common_id', $categories)->paginate();
        } else {
            $commonProcesses = $commonProcesses->paginate();
        }

        $departments = $department->all();
        $filter = [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'department_id' => $departmentId,
            'category' => $category
        ];
        return view('admin.commonProcess.index', compact('commonProcesses', 'departments', 'filter'));
    }

    public function show(CommonProcess $commonProcess)
    {
        return view('admin.commonProcess.show', compact('commonProcess'));
    }

    public function destroy(CommonProcess $commonProcess)
    {
        $commonProcess->delete();

        return response()->json([
            'status'=> 200,
            'message' => '删除成功'
        ]);
    }
}