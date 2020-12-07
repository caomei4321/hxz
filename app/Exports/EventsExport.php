<?php
namespace App\Exports;

use App\Models\Event;
/*use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;*/

class EventsExport
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

        $excel->create('上报记录导出', function ($excel) use ($cellData) {
            $excel->sheet('temporaryTask', function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
            });
        })->export('xlsx');
    }

    private function createData()
    {
        $events = Event::whereBetween('created_at',[$this->startTime, $this->endTime])->with('replies')->get();
        $cellData = [];

        $firstRow = ['上报时间', '事件类型', '事件状态', '事件地址', '上报描述', '上报人', '后续回复'];

        array_push($cellData, $firstRow);

        foreach ($events as $value) {
            $createdAt = $value->created_at->toDateTimeString();

            $category = $value->category;

            $status = $value->status;

            $address = $value->address;

            $description = $value->description;

            $userName = $value->user->name;

            $data = [
                $createdAt, $category, $status, $address, $description, $userName
            ];

            // 所有的回复放在最后
            foreach ($value->replies as $reply) {
                array_push($data, $reply->reply);
            }

            array_push($cellData, $data);
        }

        return $cellData;
    }
}