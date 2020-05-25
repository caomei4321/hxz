<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\Curl;
use App\Models\Coordinate;
use App\Models\User;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Excel;

class UsersController extends Controller
{
    public function index(User $user)
    {
        $users = $user->paginate(15);

        return view('admin.user.index', compact('users'));
    }

    public function create(User $user, UserCategory $userCategory)
    {
        $categories = $userCategory->all();
        return view('admin.user.create_and_edit', compact('user', 'categories'));
    }

    public function store(Request $request, User $user)
    {
        $data = $request->only(['name', 'phone', 'password', 'age',  'category_id']);
        $data['password'] = Hash::make($data['password']);
        $user->fill($data);
        $user->save();

        return response()->json([
            'status'=> 200,
            'message' => '添加成功'
        ]);
    }

    public function edit(User $user, UserCategory $userCategory)
    {
        $categories = $userCategory->all();
        return view('admin.user.create_and_edit', compact('user', 'categories'));
    }

    public function update(Request $request, User $user)
    {

        $data = $request->only(['name', 'phone', 'password', 'age',  'category_id']);
        if ($user->password != $data['password']) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);

        return response()->json([
            'status' => 200,
            'message' => '更新成功'
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'status'=> 200,
            'message' => '删除成功'
        ]);
    }
}
