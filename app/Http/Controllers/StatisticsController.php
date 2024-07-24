<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function showStatistics(){
 $bestSchool = DB::table('participants')
    ->join('schools', 'participants.school_id', '=', 'schools.id')
    ->join('participant_challenges', 'participants.id', '=', 'participant_challenges.participant_id')
    ->select('schools.name', DB::raw('SUM(participant_challenges.marks) as total_marks'))
    ->groupBy('schools.name')
    ->orderBy('total_marks', 'desc')
    ->first();

$worstSchool = DB::table('participants')
    ->join('schools', 'participants.school_id', '=', 'schools.id')
    ->join('participant_challenges', 'participants.id', '=', 'participant_challenges.participant_id')
    ->select('schools.name', DB::raw('SUM(participant_challenges.marks) as total_marks'))
    ->groupBy('schools.name')
    ->orderBy('total_marks', 'asc')
    ->first();

$bestCandidates = DB::table('participant_challenges')
    ->join('participants', 'participant_challenges.participant_id', '=', 'participants.id')
    ->select('participants.name', DB::raw('SUM(participant_challenges.marks) as total_marks'))
    ->groupBy('participants.name')
    ->orderBy('total_marks', 'desc')
    ->get();

$bestQuestion = DB::table('participant_challenges')
    ->select('question_id', DB::raw('SUM(correct) as total_correct'))
    ->groupBy('question_id')
    ->orderBy('total_correct', 'desc')
    ->first();

$worstQuestion = DB::table('participant_challenges')
    ->select('question_id', DB::raw('SUM(correct) as total_correct'))
    ->groupBy('question_id')
    ->orderBy('total_correct', 'asc')
    ->first();

// Pass data to the view
return view('admin.statistics', [
'bestSchool' => $bestSchool,
'worstSchool' => $worstSchool,
'bestCandidates' => $bestCandidates,
'bestQuestion' => $bestQuestion,
'worstQuestion' => $worstQuestion,
]);

}

}
