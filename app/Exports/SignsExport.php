<?php
namespace App\Exports;

use App\Models\DailyProcess;
use App\Models\Sign;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class SignsExport implements FromCollection
{
    private $startTime;
    private $endTime;
    private $departmentId;
    private $type;
    public function __construct($startTime, $endTime, $departmentId, $type)
    {
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->departmentId = $departmentId;
        $this->type = $type;
    }


    public function collection()
    {
        return collect($this->createData());
    }

    public function createData()
    {
        $query = Sign::where('created_at', '>=', $this->startTime)
            ->where('created_at', '<=', $this->endTime);
        if ($this->departmentId) {
            $users = DB::table('users')->where('department_id', $this->departmentId)->pluck('id');
            $query = $query->whereIn('user_id',$users);
        }
        if ($this->type == 1 || $this->type == 2) {
            if ($this->type == 1) {
                $query = $query->where('type', 1);
            } else {
                $query = $query->where('type', 0);
            }
        }

        $signs = $query->orderBy('created_at', 'asc')->get();

        $firstRow = ['时间', '姓名', '所属单位', '联系方式', '类型'];

        $cellData = [];
        array_push($cellData, $firstRow);

        foreach ($signs as $value) {
            $createdAt = $value->created_at;
            $userName = $value->user->name;
            $departmentName = $value->user->department->name;
            $userTel = (string)$value->user->phone;
            $type = $value->type == 1 ? '上班卡' : '下班卡';

            $data = [
                $createdAt, $userName, $departmentName, $userTel, $type
            ];

            array_push($cellData, $data);
        }
        return $cellData;
    }
}