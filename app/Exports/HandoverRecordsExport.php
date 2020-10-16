<?php
namespace App\Exports;

use App\Models\DailyProcess;
use App\Models\HandoverRecord;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class HandoverRecordsExport implements FromCollection
{
    private $startTime;
    private $endTime;
    private $departmentId;
    public function __construct($startTime, $endTime, $departmentId)
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->departmentId = $departmentId;
    }


    public function collection()
    {
        return collect($this->createData());
    }

    public function createData()
    {
        $query = HandoverRecord::where('created_at', '>=', $this->startTime)->where('created_at', '<=', $this->endTime);

        if ($this->departmentId) {
            $users = DB::table('users')->where('department_id', $this->departmentId)->pluck('id');
            $query = $query->whereIn('sender_user', $users);
        }

        $handoverRecords = $query->with(['sendUser', 'recipientUser'])->orderBy('id', 'desc')->get();

        $firstRow = ['时间', '小区', '交班人', '接班人', '交班内容'];

        $cellData = [];
        array_push($cellData, $firstRow);

        foreach ($handoverRecords as $value) {
            $createdAt = $value->created_at;
            $departmentName = $value->sendUser->department->name;
            $sendUser = $value->sendUser->name;
            $recipientUser = $value->recipientUser->name;
            $content = $value->content;

            $data = [
                $createdAt, $departmentName, $sendUser, $recipientUser, $content
            ];

            array_push($cellData, $data);
        }
        return $cellData;
    }
}