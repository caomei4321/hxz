<?php

namespace App\Http\Controllers\Admin;

use App\Models\Station;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StationsController extends Controller
{
    public function index(Station $station)
    {
        $stations = $station->orderBy('id','desc')->paginate(15);
        return view('admin.station.index', compact('stations'));
    }

    public function create(Station $station)
    {
        return view('admin.station.create_and_edit', compact('station'));
    }

    public function store(Request $request, Station $station)
    {
        $station->fill($request->only(['name']));
        $station->save();

        return response()->json([
            'status'=> 200,
            'message' => '添加成功'
        ]);
    }

    public function edit(Station $station)
    {
        return view('admin.station.create_and_edit', compact('station'));
    }

    public function update(Station $station, Request $request)
    {
        $station->update($request->only(['name']));
        return response()->json([
            'status' => 200,
            'message' => '更新成功'
        ]);
    }

    public function destroy(Station $station)
    {
        $station->appointmentRecord()->delete();
        $station->delete();
        $station->delete();
        return response()->json([
            'status' => 200,
            'message' => '删除成功'
        ]);
    }
}
