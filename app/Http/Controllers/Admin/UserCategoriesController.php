<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserCategoriesController extends Controller
{
    public function index(UserCategory $userCategory)
    {
        $userCategories = $userCategory->paginate(15);
        return view('admin.userCategory.index', compact('userCategories'));
    }

    public function create(UserCategory $userCategory)
    {
        return view('admin.userCategory.create_and_edit', compact('userCategory'));
    }

    public function store(Request $request, UserCategory $userCategory)
    {
        $userCategory->fill($request->only(['name']));
        $userCategory->save();

        return response()->json([
            'status'=> 200,
            'message' => '添加成功'
        ]);
    }

    public function edit(UserCategory $userCategory)
    {
        return view('admin.userCategory.create_and_edit', compact('userCategory'));
    }

    public function update(UserCategory $userCategory, Request $request)
    {
        $userCategory->update($request->only(['name']));
        return response()->json([
            'status' => 200,
            'message' => '更新成功'
        ]);
    }

    public function destroy(UserCategory $userCategory)
    {
        $userCategory->delete();
        return response()->json([
            'status' => 200,
            'message' => '删除成功'
        ]);
    }
}
