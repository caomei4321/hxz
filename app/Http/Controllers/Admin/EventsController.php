<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventsController extends Controller
{
    public function index(Event $event)
    {
        $events = $event->orderBy('id', 'desc')->paginate(15);
        return view('admin.event.index', compact('events'));
    }

    public function show(Event $event)
    {
        return view('admin.event.show', compact('event'));
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json([
            'status'=> 200,
            'message' => '删除成功'
        ]);
    }
}
