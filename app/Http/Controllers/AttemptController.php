<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attempt;
use Illuminate\Support\Facades\DB;

class AttemptController extends Controller
{
    public function index()
    {
        $attempts = DB::table('attempts')
            ->select('participant', 'question', 'status', DB::raw('COUNT(*) as count'))
            ->groupBy('participant', 'question', 'status')
            ->orderBy('participant')
            ->orderBy('question')
            ->get();

        return view('attempts', compact('attempts'));
    }
}