<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\School;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(){
        // Get yesterday's date
        $yesterday = Carbon::yesterday()->toDateString();

        // Fetch yesterday's data from the database
        $yesterdaySchool = School::whereDate('created_at', $yesterday)->sum('school_name');
        $yesterdayParticipant = Participant::whereDate('created_at', $yesterday)->sum('username');

        $todaySchool= School::whereDate('created_at', now()->format('Y-m-d'))->sum('school_name');
        $totalSchool = School::sum('school_name');
        $todayParticipant = Participant::whereDate('created_at', now()->format('Y-m-d'))->sum('username');
        $totalParticipant = Participant::sum('username');

        return view('admin.home', [
            'yesterdaySchool' => $yesterdaySchool,
            'yesterdayParticipant' => $yesterdayParticipant,
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
