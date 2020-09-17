<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use App\Models\Message;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendMessage;

class MessagesController extends Controller
{
    public function index(Message $message)
    {
        $messages = $message->orderBy('id', 'desc')->paginate(15);
        return view('admin.message.index', compact('messages'));
    }

    public function create(UserCategory $userCategory, Department $department)
    {
        $userCategories = $userCategory->all();

        $departments = $department->all();

        return view('admin.message.add', compact('userCategories', 'departments'));
    }

    public function store(Request $request,Message $message)
    {
        $data = $request->only(['title', 'content']);

        $message->fill($request->only(['title', 'content']));
        $message->save();

        $message->users()->attach($request->users);

        foreach ($request->users as $key => $value) {

//            $job = new SendMessage($value, '0-wReXMBf0gg7Br3HRaZ-lW5x55hu5ot_d5k3YncJgc', 'pages/basics/message?id='.$message->id, $data);
            $job = new SendMessage($value, 'FLIa3sZqChPa9T2rj4xu7OeYNNT_-3vpSBiTIiJ__t8', 'pages/basics/message?id='.$message->id, $data);
            dispatch($job);
        }

        return response()->json([
            'status'=> 200,
            'message' => '添加成功'
        ]);
    }

    public function destroy(Message $message)
    {
        $message->delete();

        return response()->json([
            'status'=> 200,
            'message' => '删除成功'
        ]);
    }
}
