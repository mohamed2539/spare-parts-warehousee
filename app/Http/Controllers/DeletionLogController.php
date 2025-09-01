<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DeletionLog;

class DeletionLogController extends Controller
{
    public function index()
    {
        $logs = DeletionLog::latest()->paginate(20);
        return view('logs.index', compact('logs'));
    }
}
