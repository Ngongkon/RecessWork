<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\Question;
use App\Models\Participant;
use App\Mail\ParticipantReportMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;


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



    // Display the form to send emails to participants
    public function showSendEmailsForm()
    {
        $participants = Participant::all();
        return view('admin.send-emails',compact('participants'));
    }

    // Handle the form submission and send emails
 public function sendEmailsToParticipants(Request $request){

// $questions = Question::with('answers')->get();

// // Load the view and generate the PDF
// $pdf = PDF::loadView('pdf.report', compact('questions'));

// // Fetch participants' emails (assuming you have a Participant model and their emails)
// $participants = Participant::where('email');

// foreach ($participants as $participant) {
//     try {
//     Mail::to($participant->email)->send(new ParticipantReportMail($pdf));

//     Log::info('Email sent to: ' . $participant->email);
// } catch (\Exception $e) {
//     Log::error('Failed to send email to: ' . $participant->email . ' Error: ' . $e->getMessage());
// }
// }

// return back()->with('success', 'Reports sent successfully!');

// 
$request->validate([
    'subject' => 'required|string|max:255',
    'message' => 'required|string',
    'file' => 'required|file|mimes:pdf|max:2048', // Ensure it’s a PDF and size is under 2MB
    'recipients' => 'required|array',
    'recipients.*' => 'email'
]);

$file = $request->file('file');
$fileContent = file_get_contents($file->getRealPath());
$fileName = $file->getClientOriginalName();

$recipients = $request->input('recipients');

foreach ($recipients as $email) {
    try {
        Mail::to($email)->send(new ParticipantReportMail($fileContent, $fileName, $request->subject, $request->message));
        Log::info('Email sent to: ' . $email);
    } catch (\Exception $e) {
        Log::error('Failed to send email to: ' . $email . ' Error: ' . $e->getMessage());
    }
}

return back()->with('success', 'Emails sent successfully!');

}

}
