<?php
namespace App\Exports;

use App\Models\CommonProcess;
use App\Models\CommonTask;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class TemporaryTaskExport implements FromCollection
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
        $commonTasks = CommonTask::whereBetween('created_at',[$this->startTime, $this->endTime])->where('category', '临时任务')->get();


        $cellData = [];

        $firstRow = [ '任务标题', '任务内容', '小区名称', '任务状态', '下发时间', '处理完成时间'];

        array_push($cellData, $firstRow);

        foreach ($commonTasks as $value) {

            $title = $value->title;
            $content=$value->content;
            $category=$value->category;

            $status = $value->status;
            if ($status==1){
                $status=' `进行中';
            }else{
                $status='已完成';
            }
            $created_at = $value->created_at;
            $updated_at = $value->updated_at;

            $data = [
                $title, $content, $category, $status, $created_at, $updated_at
            ];

            array_push($cellData, $data);
        }

        return $cellData;
    }
}