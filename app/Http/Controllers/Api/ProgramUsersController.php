<?php

namespace App\Http\Controllers\Api;

use App\Models\ProgramUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramUsersController extends Controller
{
    /*
     * 小程序上报问题
     * */
    public function matterStore(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'address' => 'required|max:255',
            'contents' => 'required',
            'image' => 'required',
        ]);

        $data = [
            'title' => $request->title,
            'address' => $request->address,
            'content' => $request->contents,
            'from' => 3
        ];
        $path = Storage::disk('public')->putFile('miniProgramImg',$request->file('image'));
        $data['image'] = '/storage/' . $path;

        $this->user()->matters()->create($data);

        return $this->success('上报成功');
    }

    /*
     * 用户历史上报记录
     * */
    public function historyMatters()
    {
        $matters = $this->user()->matters();

        return $matters;
    }
}