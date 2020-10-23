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
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class TemporaryTasksController extends Controller
{
    public function index(CommonTask $commonTask, Request $request)
    {
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
    public function export(Request $request)
    {
        $startTime = $request->start_time ? $request->start_time : date('Y-m-d', time());
        $endTime = $request->end_time ? $request->end_time : date('Y-m-d', strtotime("+1 day"));
        $departmentId =  $request->department_id ? $request->department_id : '';


        return Excel::download(new TemporaryTaskExport($startTime, $endTime, $departmentId), '临时任务记录导出.xls');
    }

}