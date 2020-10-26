<?php
namespace App\Exports;

use App\Models\CommonTask;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class TemporaryTaskExport implements FromCollection, ShouldAutoSize, WithColumnFormatting
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
        $commonTasks = CommonTask::whereBetween('created_at',[$this->startTime, $this->endTime])->where('category', '临时任务')->with('users.department')->get();
        $cellData = [];

        $firstRow = ['下发时间', '处理完成时间', '任务标题', '任务内容', '执行人', '小区名称', '处理描述'];

        array_push($cellData, $firstRow);

        //Carbon::now()->getTimestamp()
        foreach ($commonTasks as $value) {
            $createdAt = $value->created_at->getTimestamp();
            if ($value->status){
                $updated_at = '';
            }else{
                $updated_at = $value->updated_at->getTimestamp();
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

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DATETIME, //日期
            'B' => NumberFormat::FORMAT_DATE_DATETIME, //日期
        ];
    }
}