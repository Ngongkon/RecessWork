<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Challenge;
use App\Models\Participant;
use App\Models\Question;
use App\Models\School;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(){
        // Get yesterday's date
        $yesterday = Carbon::yesterday()->toDateString();

        // Get today's date
        $today = Carbon::today()->toDateString();


        // Fetch yesterday's data from the database
        $yesterdaySchool = Answer::count();
       // $yesterdayParticipant = School::whereDate('created_at', $yesterday)->count();

        $todaySchool= Question::count();
        $totalSchool = School::count();
        $todayParticipant = Challenge::count();
        $totalParticipant = Participant::count();

        return view('admin.home', [
            'yesterdaySchool' => $yesterdaySchool,
           // 'yesterdayParticipant' => $yesterdayParticipant,
            'todaySchool' => $todaySchool,
            'totalSchool' => $totalSchool,
            'todayParticipant' => $todayParticipant,
            'totalParticipant' => $totalParticipant,
        ]);
    }
    public function user_home(){
        return view('user.dashboard');
    }
    public function contact_us(){
        return view('contact');
    }
    public function about_us(){
        return view('about');
    }
    public function home_us(){
        return view('welcomepage');
    }


}
