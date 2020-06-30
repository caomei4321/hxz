<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\ImageUploadHandler;
use App\Models\Manual;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManualsController extends Controller
{
    public function index(Manual $manual)
    {
        $manuals = $manual->orderBy('sort', 'asc')->orderBy('id', 'asc')->paginate();
        return view('admin.manual.index', compact('manuals'));
    }

    public function create(Manual $manual)
    {
        return view('admin.manual.create_and_edit', compact('manual'));
    }

    public function store(Request $request, Manual $manual)
    {
        $manual->fill($request->only(['title', 'sort', 'detail']));
        $manual->save();

        return redirect()->route('admin.manual.index');
    }

    public function edit(Manual $manual)
    {
        return view('admin.manual.create_and_edit', compact('manual'));
    }

    public function update(Manual $manual, Request $request)
    {
        $manual->update($request->only(['title', 'sort', 'detail']));

        return redirect()->route('admin.manual.index');
    }

    public function destroy(Manual $manual)
    {
        $manual->delete();

        return response()->json([
            'message' => '删除成功',
            'status' => 200
        ]);
    }

    public function updateSort(Manual $manual, Request $request)
    {
        $manual->update([
            'sort' => $request->sort
        ]);

        return response()->json([
            'message' => '修改成功',
            'status' => 200
        ]);
    }


    // 保存富文本上传的图片
    public function saveImg(Request $request, ImageUploadHandler $uploadHandler)
    {
        $res = $uploadHandler->save($request->file('img'), 'manuals', 'manual');

        if ($res['path']) {
            return response()->json([
                'errno' => 0,
                'data' => [
                    env('APP_URL').$res['path']
                ]
            ]);
        }
    }
}
