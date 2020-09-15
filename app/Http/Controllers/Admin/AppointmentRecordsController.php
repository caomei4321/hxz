<?php

namespace App\Http\Controllers\Admin;

use App\Models\AppointmentRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppointmentRecordsController extends Controller
{
    public function index(AppointmentRecord $appointmentRecord)
    {
        $appointmentRecords = $appointmentRecord->paginate(15);
        return view('admin.appointmentRecord.index', compact('appointmentRecords'));
    }

    public function update(AppointmentRecord $appointmentRecord, Request $request)
    {
        $appointmentRecord->update($request->only(['status']));
        return response()->json([
            'status' => 200,
            'message' => '更新成功'
        ]);
    }

    public function destroy(AppointmentRecord $appointmentRecord)
    {
        $appointmentRecord->delete();
        return response()->json([
            'status' => 200,
            'message' => '删除成功'
        ]);
    }

    /*
     *  status : 0 表示未处理， 1 表示预约成功（同意巡查）， 2 表示预约失败（拒绝预约）
     * */
    public function changeStatus(AppointmentRecord $appointmentRecord, Request $request)
    {
        $appointmentRecord->status = $request->status;
        $appointmentRecord->save();

        return response()->json([
            'status' => 200,
            'message' => '修改成功'
        ]);
    }
}
