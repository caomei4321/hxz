<?php

namespace App\Http\Controllers\Admin;

use App\Models\HandoverRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HandoverRecordsController extends Controller
{
    public function index(HandoverRecord $handoverRecord)
    {
        $handoverRecords = $handoverRecord->with(['sendUser', 'recipientUser'])->orderBy('id', 'desc')->paginate();

        return view('admin.handoverRecord.index', compact('handoverRecords'));
    }

    public function destroy(HandoverRecord $handoverRecord)
    {
        $handoverRecord->delete();

        return response()->json([
            'status'=> 200,
            'message' => '删除成功'
        ]);
    }
}
