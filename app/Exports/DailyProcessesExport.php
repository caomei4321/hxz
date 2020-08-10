<?php
namespace App\Exports;

use App\Models\DailyProcess;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DailyProcessesExport implements FromCollection, WithDrawings
{
    private $startTime;
    private $endTime;
    private $departmentId;
    private $path;
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

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setPath(public_path($this->path));
        $drawing->setHeight(60);

        return $drawing;
    }

    public function createData()
    {
        if ($this->departmentId) {
            $users = DB::table('users')->where('department_id', $this->departmentId)->pluck('id');
            $dailyProcesses = DailyProcess::whereBetween('created_at', [$this->startTime,$this->endTime])
                ->whereIn('user_id',$users)->orderBy('created_at', 'asc')->get();
        } else {
            $dailyProcesses = DailyProcess::whereBetween('created_at', [$this->startTime,$this->endTime])
                ->orderBy('created_at', 'desc')->get();
        }

        $firstRow = ['时间', '任务标题', '执行人', '所属单位', '联系方式', '处理地点', '处理描述', '图片'];

        $cellData = [];
        array_push($cellData, $firstRow);

        foreach ($dailyProcesses as $value) {
            $createdAt = $value->created_at;
            $title = $value->dailyTask->title;
            $userName = $value->user->name;
            $departmentName = $value->user->department->name;
            $userTel = $value->user->phone;
            $address = $value->address;
            $description = $value->description;

            $data = [
                $createdAt, $title, $userName, $departmentName, $userTel, $address, $description
            ];
            foreach(json_decode($value->photo) as $path) {
                if ($path) {
                    $this->path = $path;
                    array_push($data, $this->drawings());
                }
            }


            array_push($cellData, $data);
        }
        return $cellData;
    }
}