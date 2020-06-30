<?php

namespace App\Http\Controllers\Api;

use App\Models\Manual;
use Illuminate\Http\Request;

class ManualController extends Controller
{
    public function index(Manual $manual)
    {
        $manualList = $manual->orderBy('sort', 'asc')->orderBy('id', 'asc')->get();

        return $this->message($manualList);
    }
}
