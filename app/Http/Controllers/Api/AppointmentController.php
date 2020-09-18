<?php

namespace App\Http\Controllers\Api;

use App\Jobs\SendMessage;
use App\Models\HandoverRecord;
use App\Models\Station;
use App\Models\User;
use Dingo\Api\Auth\Auth;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // 返回岗亭信息
    public function getStations()
    {
        $stations = Station::all();

       return $this->message($stations);
    }
    //  返回预约记录
    public function getRecord()
    {
        $user = Auth()->guard('api')->user();
        $records = $user->appointmentRecord()->with('station')->orderBy('id', 'desc')->get();
        return $this->message($records);
    }
    /*
     *  保存预约信息
     *  status : 0 表示未处理， 1 表示预约成功（同意巡查）， 2 表示预约失败（拒绝预约）
     * */

    public function storeAppointment(Request $request)
    {
        $user = Auth()->guard('api')->user();
        $user->appointmentRecord()->create([
            'station_id' => $request->station_id,
            'appointment_time' => $request->appointment_time,
            'status' => 0
        ]);

        return $this->success([
            'message' => '提交成功'
        ]);
    }
    // 设置交接信息为已读
    public function readHandover(Request $request)
    {
        $user = Auth()->guard('api')->user();

        $user->handoverRecipient()->where('id', $request->id)->update([
            'read_time' => date('Y-m-d H:i:s', time())
        ]);

        return $this->success([
            'message' => '修改成功',
            'read_time' => date('Y-m-d H:i:s', time())
        ]);
    }
}
