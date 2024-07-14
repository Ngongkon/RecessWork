<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Challenge;

class ChallengeController extends Controller
{
    public function showSetParametersForm()
    {
        $challenges = Challenge::all();
        
        return view('admin.challenge', compact('challenges'));
    }
    public function setParameters(Request $request)
    {
    
    $challenge = new Challenge();
        $challenge->challengeName = $request->challengeName;
        $challenge->title = $request->title;
        $challenge->start_date = $request->start_date;
        $challenge->end_date = $request->end_date;
        $challenge->duration = $request->duration;
        $challenge->num_questions = $request->num_questions;
        $challenge->save();

       return redirect()->back()->with('success', 'Challenge parameters set successfully');
    }

   
}
