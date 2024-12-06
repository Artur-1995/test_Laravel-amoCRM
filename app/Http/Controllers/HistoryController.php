<?php

namespace App\Http\Controllers;

use App\Models\Action;

class HistoryController extends Controller
{
    public function __invoke()
    {
        $logs = Action::orderByDesc('created_at')->paginate(10)->fragment('logs');

        return view('history', compact('logs'));
    }
}
