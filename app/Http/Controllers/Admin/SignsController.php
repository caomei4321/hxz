<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SignsExport;
use App\Models\Department;
use App\Models\Sign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SignsController extends Controller
{
    public function index(Sign $sign, Request $request)
    {
        $startTime = $request->start_time ? $request->start_time : '';
        $endTime = $request->end_time ? $request->end_time : '';
        $departmentId =  $request->department_id ? $request->department_id : '';
        $type = $request->type ? $request->type : 3;
        $departments = Department::all();

        $query = $sign;

        if ($startTime) {
            $query = $query->where('created_at', '>=', $startTime);
        }
        if ($endTime) {
            $query = $query->where('created_at', '<=', $endTime);
        }

        if ($departmentId) {
            $users = DB::table('users')->where('department_id', $departmentId)->pluck('id');
            $query = $query->whereIn('user_id', $users);
        }

        if ($type == 2 || $type == 1) {
            if ($type == 2) {
                $query = $query->where('type', 0);
            } else {
                $query = $query->where('type', $type);
            }
        }

        $signs = $query->orderBy('id', 'desc')->paginate(15);

        $filter = [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'type' => $type,
            'department_id' => $departmentId
        ];

        return view('admin.sign.index', compact('signs', 'departments', 'filter'));
    }

    public function export(Request $request)
    {
        $startTime = $request->start_time ? $request->start_time : date('Y-m-d', time());
        $endTime = $request->end_time ? $request->end_time : date('Y-m-d', strtotime("+1 day"));
        $departmentId =  $request->department_id ? $request->department_id : '';
        $type = $request->type ? $request->type : 3;

        return Excel::download(new SignsExport($startTime, $endTime, $departmentId, $type), '签到记录导出.xls');
    }
}
