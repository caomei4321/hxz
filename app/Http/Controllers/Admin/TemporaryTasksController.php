<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TemporaryTaskExport;
use App\Jobs\SendMessage;
use App\Models\CommonProcess;
use App\Models\CommonTask;
use App\Models\Department;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;

class TemporaryTasksController extends Controller
{
    public function index(CommonTask $commonTask, Request $request)
    {
        if (Auth::user()->department_id) {
            $departmentId = Auth::user()->department_id;
            $commonIds = DB::select("SELECT b.common_id FROM (select id FROM `users` WHERE department_id = $departmentId) as a, `user_has_common_tasks` as b WHERE b. user_id = a.id");
            $commons = [];  // 包含当前小区的所有任务id
            foreach ($commonIds as $commonId) {
                array_push($commons, $commonId->common_id);
            }
            $commonTask = $commonTask->whereIn('id', $commons);
        }

        $startTime = $request->start_time ? $request->start_time : date('Y-m-d', time());
        $endTime = $request->end_time ? $request->end_time : date('Y-m-d', strtotime("+1 day"));

        $commonTasks =
            $commonTask->where('category', '临时任务')->whereBetween('created_at',[$startTime,$endTime])->with(['users' =>
                function($query) {$query->with('department');}])
                        ->orderBy('id', 'desc')->paginate();
/*        $commonTasks = $commonTask->whereBetween('up_at',[$startTime,$endTime]);*/





        $filter = [
            'start_time' => $startTime,
            'end_time' => $endTime,
            //'category' => $category
        ];
       /* $res =CommonTask ::whereDate('created_at', '>=', $startTime)
            ->whereDate('created_at', '<=', $endTime)
            ->get();*/


        return view('admin.temporaryTask.index', compact('commonTasks','filter'));
    }


    public function create(UserCategory $userCategory, Department $department, Request $request)
    {
        $userCategories = $userCategory->all();

        $departments = $department->all();
        $categoryId = $request->category_id;

        if ($categoryId == null) {
            $arr = array_column($userCategories->toArray(),"name", "id");
            $categoryId = array_search('物业', $arr);
        }

        $filter = [
            'department_id' => $request->department_id,
            'category_id' => $categoryId
        ];

        return view('admin.temporaryTask.add', compact('userCategories', 'departments', 'filter'));
    }

    public function store(Request $request, CommonTask $commonTask)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'users' => 'required'
        ], [
            'title.required' => '请填写标题',
            'content.required' => '请填写内容',
            'users.required' => '请选择执行人'
        ]);
        $data = $request->only(['title', 'content']);
        $data['status'] = 1;  //刚添加的任务默认状态为进行中
        $data['category'] = '临时任务';
        $commonTask->fill($data);
        $commonTask->save();

        $commonTask->users()->attach($request->users);

        foreach ($request->users as $key => $value) {
           // $job = new SendMessage($value, '0-wReXMBf0gg7Br3HRaZ-lW5x55hu5ot_d5k3YncJgc', 'pages/basics/temporary?id='.$commonTask->id, $data);
            //$job = new SendMessage($value, 'FLIa3sZqChPa9T2rj4xu7OeYNNT_-3vpSBiTIiJ__t8', 'pages/basics/temporary?id='.$commonTask->id, $data);
            $job = new SendMessage($value, 'CcK9cjdSeQrwBnh0kIpjjGcPl0BnKI6EYZBSmweszPY', 'pages/basics/temporary?id='.$commonTask->id, [
                'thing1' => [
                    'value' => $data['title']
                ],
                'thing5' => [
                    'value' => $data['content']
                ]
            ]);
            dispatch($job);
        }

        return redirect()->route('admin.temporaryTask.index');
    }

    public function show(CommonTask $commonTask)
    {
        $commonTasks = $commonTask->users;

        return view('admin.temporaryTask.show', compact('commonTasks', 'commonTask'));
    }

    public function destroy(CommonTask $commonTask)
    {
        $commonTask->users()->detach();

        $commonTask->delete();

        return response()->json([
            'status'=> 200,
            'message' => '删除成功'
        ]);
    }

    /*
     * 修改任务状态
     *
     * status: 1 表示进行中； 0 表示已完结
     * */
    public function changeStatus(CommonTask $commonTask)
    {
        if ($commonTask->status == 1) {
            $commonTask->status = 0;
        } else {
            $commonTask->status = 1;
        }
        $commonTask->save();

        return response()->json([
            'message' => '修改成功',
            'status' => 200
        ]);
    }
    public function export(Request $request, Excel $excel)
    {
        $startTime = $request->start_time ? $request->start_time : date('Y-m-d', time());
        $endTime = $request->end_time ? $request->end_time : date('Y-m-d', strtotime("+1 day"));
        $departmentId =  $request->department_id ? $request->department_id : '';

        $cellData = [
            ['什么','22'],
            ['22','33'],
            ['33','44']
        ];
        //dd($request);
        $firstRow = ['姓名','发现问题数量','开始时间','结束时间', '时长(分钟)', '里程(KM)', '总时长(分钟)', '总里程(KM)'];
        $excel->create('临时任务记录导出', function ($excel) use ($cellData,$firstRow) {
            $excel->sheet('matter', function ($sheet) use ($cellData,$firstRow) {
                $sheet->prependRow(1, $firstRow);
                $sheet->row($cellData);
            });
        })->export('xls');
         /*$excel->create('临时任务记录导出', function ($excel) use ($cellData) {
                $excel->sheet('temporaryTask', function ($sheet) use ($cellData) {
                    $sheet->row([
                        ['11','22'],
                        ['22','33'],
                        ['33','44']
                    ]);
                    //$sheet->row($cellData);
                });
        })->export('xls');*/
         //new TemporaryTaskExport($startTime, $endTime, $departmentId, $excel);
    }

}