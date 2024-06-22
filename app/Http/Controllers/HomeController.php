<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('admin.dashboard');
    }
    public function user_home(){
        return view('dashboard');
    }
    public function contact_us(){
        return view('contact');
    }
    public function about_us(){
        return view('about');
    }
}
