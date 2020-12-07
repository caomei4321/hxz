<?php
namespace App\Exports;

use App\Models\CommonTask;
use Carbon\Carbon;
use Maatwebsite\Excel\Excel;

/*use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;*/

class TemporaryTaskExport
{
    private $startTime;
    private $endTime;
    private $departmentId;

    public function __construct($startTime, $endTime, $departmentId, $excel)
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->departmentId = $departmentId;

        $cellData = $this->createData();

        $excel->create('临时任务记录导出', function ($excel) use ($cellData) {
            $excel->sheet('temporaryTask', function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

    public function createData()
    {
        $commonTasks = CommonTask::whereBetween('created_at',[$this->startTime, $this->endTime])->where('category', '临时任务')->with('users.department')->get();
        $cellData = [];

        $firstRow = ['下发时间', '处理完成时间', '任务标题', '任务内容', '执行人', '小区名称', '处理描述'];

        array_push($cellData, $firstRow);

        foreach ($commonTasks as $value) {
            $createdAt = $value->created_at->toDateTimeString();
            if ($value->status){
                $updated_at = '';
            }else{
                $updated_at = $value->updated_at->toDateTimeString();
            }
            $title = $value->title;
            $content = $value->content;
            $users = '';
            $departments = '';

            $data = [
                $createdAt, $updated_at, $title, $content, $users, $departments
            ];
            // 拼接任务的所有人员和部门
            foreach ($value->users as $user) {
                $users = $user->name.','.$users;
                $departments = $user->department->name.','.$departments;
                array_push($data, $user->pivot->description);
            }
            $data[4] = $users;
            $data[5] = $departments;

            array_push($cellData, $data);
        }

        return $cellData;
    }
}