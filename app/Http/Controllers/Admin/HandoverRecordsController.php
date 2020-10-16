<?php

namespace App\Http\Controllers\Admin;

use App\Exports\HandoverRecordsExport;
use App\Exports\SignsExport;
use App\Models\Department;
use App\Models\HandoverRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class HandoverRecordsController extends Controller
{
    public function index(HandoverRecord $handoverRecord, Request $request)
    {
        $startTime = $request->start_time ? $request->start_time : '';
        $endTime = $request->end_time ? $request->end_time : '';
        $departmentId =  $request->department_id ? $request->department_id : '';
        $departments = Department::all();

        $query = $handoverRecord;
        if ($startTime) {
            $query = $query->where('created_at', '>=', $startTime);
        }
        if ($endTime) {
            $query = $query->where('created_at', '<=', $endTime);
        }

        if ($departmentId) {
            $users = DB::table('users')->where('department_id', $departmentId)->pluck('id');
            $query = $query->whereIn('sender_user', $users);
        }

        $filter = [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'department_id' => $departmentId
        ];

        $handoverRecords = $query->with(['sendUser', 'recipientUser'])->orderBy('id', 'desc')->paginate();

        return view('admin.handoverRecord.index', compact('handoverRecords', 'departments', 'filter'));
    }

    public function destroy(HandoverRecord $handoverRecord)
    {
        $handoverRecord->delete();

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

        return Excel::download(new HandoverRecordsExport($startTime, $endTime, $departmentId), '交接班记录导出.xls');
    }
}
