<?php

namespace App\Http\Controllers;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\challengeRequest;
use App\Models\Challenge;
use App\Models\Question;

class ChallengeController extends Controller
{

public function storeChallenge(Request $request)
    {
       
     // Get the count of questions in the questions table
        $totalQuestions = Question::count();

          // Determine the maximum number of questions to select (10 or less if there are fewer questions)
        $maxQuestions = min(10, $totalQuestions);

        // Ensure there are questions available
        if ($totalQuestions > 0) {
            // Generate a random number of questions between 1 and the maximum allowed (10 or total questions)
           $randomNumQuestions = rand(10, $maxQuestions);

           // Get a random subset of question IDs
       // $randomQuestions = Question::inRandomOrder()->limit($randomNumQuestions)->pluck('question_text');

            // Create the challenge with the random number of questions
            $challenges = new Challenge();
            $challenges->challenge_name = $request->challenge_name;
            $challenges->start_date = $request->start_date;
            $challenges->end_date = $request->end_date;
            $challenges->duration = $request->duration;
            $challenges->num_questions = $randomNumQuestions;
            $challenges->save();
            // Associate the selected questions with the challenge
         // $challenges->challengeQuestions()->attach($randomQuestions);

            return redirect()->route('challenges')->with('message', 'Challenge added successfully with ' . $randomNumQuestions . ' questions!');
        } else {
            return redirect()->route('challenges')->with('message', 'No questions available to assign.');
        }
    }
    public function index(){
        return view('admin.challenge');
    }

}
