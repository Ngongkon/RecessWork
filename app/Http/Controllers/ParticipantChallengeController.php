<?php

namespace App\Http\Controllers;

use App\Models\Participant_Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipantChallengeController extends Controller
{
    public function index()
    {
        
        $participantChallenges = DB::table('participant_challenges')
            ->select('participant', 'challenge', 'marks' ,DB::raw('COUNT(*) as count'))
            ->groupBy('participant', 'challenge', 'marks')
            ->orderBy('participant')
            ->orderBy('challenge')
            ->get();
        return view('participant_challenge', compact('participantChallenges'));
    }
}