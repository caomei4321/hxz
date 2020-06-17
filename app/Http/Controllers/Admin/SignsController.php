<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SignsController extends Controller
{
    public function index(Sign $sign)
    {
        $signs = $sign->orderBy('id', 'desc')->paginate();
        return view('admin.sign.index', compact('signs'));
    }
}
