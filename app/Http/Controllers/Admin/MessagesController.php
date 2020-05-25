<?php

namespace App\Http\Controllers\Admin;

use App\Models\Message;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessagesController extends Controller
{
    public function index(Message $message)
    {
        $messages = $message->paginate(15);
        return view('admin.message.index', compact('messages'));
    }

    public function create(UserCategory $userCategory)
    {
        $userCategories = $userCategory->all();

        return view('admin.message.add', compact('userCategories'));
    }

    public function store(Request $request,Message $message)
    {
        $message->fill($request->only(['title', 'content']));
        $message->save();

        $message->users()->attach($request->users);

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
