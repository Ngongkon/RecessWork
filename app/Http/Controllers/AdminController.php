<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\School;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AnswersImpor;
use App\Imports\QuestionsImport;
use App\Models\Question;
use App\Models\Answer;


class AdminController extends Controller
{
    public function addSchool()
    {
        return view('admin.add-school');
    }
 public function addnewSchool(Request $request)
    {
        $schools = new School();
       
        $schools->school_name = $request->school_name;
        $schools->district = $request->district;
        $schools->school_registration_number = $request->school_registration_number;
        $schools->representative_name = $request->representative_name;
        $schools->representative_email = $request->representative_email;
        $schools->save();
        return redirect()->back()->with('message', 'New school Added');
    }
    public function all_schools()
    {
        $schools = School::all();
        return view('admin.all-schools')->with('schools',$schools);
    }
    public function all_participants()
    {
        $participants = Participant::all();
        return view('admin.participant')->with('participants',$participants);
    }
    
    public function uploadQuestions(Request $request)
    {
        $request->validate([
            'questions_file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('questions_file');

        Excel::import(new QuestionsImport, $file);

        return redirect()->back()->with('success', 'Questions uploaded successfully.');
    }

    public function uploadAnswers(Request $request)
    {
        $request->validate([
            'answers_file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('answers_file');

        Excel::import(new AnswersImpor, $file);

        return redirect()->back()->with('success', 'Answers uploaded successfully.');
    }
    public function all_questions()
    {
        //$questions = Question::all();,compact('questions')
        return view('admin.question');
    }

    public function all_answers()
    {
        //$answers = Answer::all();,compact('answers')
        return view('admin.answer');
    }
    
}
