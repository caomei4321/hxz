<?php
namespace App\Exports;

use App\Models\CommonProcess;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class TemporaryProcessesExport implements FromCollection
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
        if ($this->departmentId) {
            $users = DB::table('users')->where('department_id', $this->departmentId)->pluck('id');
            $commonProcesses =CommonProcess::whereBetween('up_at',[$this->startTime, $this->endTime])->whereIn('user_id', $users);
        } else {
            $commonProcesses =CommonProcess::whereBetween('up_at',[$this->startTime, $this->endTime]);
        }

        $categories = DB::table('common_tasks')->where('category', '临时任务')->pluck('id');
        $commonProcesses = $commonProcesses->whereIn('common_id', $categories)->orderBy('created_at', 'asc')->get();

        $cellData = [];

        $firstRow = ['时间', '任务标题', '执行人', '所属单位', '联系方式', '处理地点', '处理描述'];

        array_push($cellData, $firstRow);

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

        return $cellData;
    }
}