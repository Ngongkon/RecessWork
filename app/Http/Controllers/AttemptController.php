<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attempt;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Challenge;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;

class AttemptController extends Controller
{


    public function start($challengeId, $request)
    {
        $challenge = Challenge::findOrFail($challengeId);

        // Randomly select the number of questions set in the challenge parameters
        $questions = Question::where('challengeName', $challengeId)
            ->inRandomOrder()
            ->limit($challenge->num_questions)
            ->get();

        // Create a new attempt
        $attempt = new Attempt();
        $attempt->username = $request->username;
        $attempt->score = $request->score;
        $attempt->school_registration_number= $request->school_registration_number;
        $attempt->challengeName = $request->challengenName;
        $attempt->save();

        return compact('challenge', 'questions', 'attempt');
    }


    // public function submitAttempt(Request $request)
    // {
    //     $attempt = Attempt::find($request->attempt_id);
    //     $totalScore = 0;

    //     foreach ($request->answers as $questionId => $givenAnswer) {
    //         $correctAnswer = Answer::where('question_id', $questionId)->first();
    //         $marksObtained = ($correctAnswer && $correctAnswer->answer_text == $givenAnswer) ? $correctAnswer->marks : 0;
    //         $totalScore += $marksObtained;

    //         // Calculate time taken for this question based on the attempt start time
    //         $timeTaken = now()->diffInSeconds($attempt->start_time);

    //         Result::create([
    //             'attempt_id' => $attempt->id,
    //             'question_id' => $questionId,
    //             'given_answer' => $givenAnswer,
    //             'marks_obtained' => $marksObtained,
    //             'time_taken' => $timeTaken, // Time taken for this question
    //         ]);
    //     }

    //     $attempt->update([
    //         'end_time' => now(),
    //         'score' => $totalScore
    //     ]);

    //     return response()->json(['message' => 'Attempt submitted successfully', 'score' => $totalScore]);
    // }

    public function all_attempts(){
        $attempts = Attempt::all();
        return view('admin.all-attempt')->with('attempts', $attempts);
    }
    public function all_results(){
        $results = Result::all();
        return view('admin.all-result')->with('results', $results);
    }
    



 }
