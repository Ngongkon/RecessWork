<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttemptController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Route;


// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard')->middleware(['auth','user']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
route::get('admin/dashboard',[HomeController::class,'index'])->middleware(['auth','admin']);
route::get('dashboard',[HomeController::class,'user_home'])->name('dashboard')->middleware(['auth','user']);
route::get('/contact',[HomeController::class,'contact_us'])->name('contact');
route::get('/about',[HomeController::class,'about_us'])->name('about');
route::get('/',[HomeController::class,'home_us']);
route::get('addingSchool',[AdminController::class,'addSchool'])->middleware(['auth','admin']);
route::post('add-new-school',[AdminController::class,'addnewSchool'])->middleware(['auth','admin']);
route::get('participant',[AdminController::class,'all_participants'])->middleware(['auth','admin']);
route::get('allSchools',[AdminController::class,'all_schools'])->middleware(['auth','admin']);
route::post('uploadingAnswers',[AdminController::class,'uploadAnswers'])->middleware(['auth','admin']);
route::post('uploadingQuestions',[AdminController::class,'uploadQuestions'])->middleware(['auth','admin']);
route::get('allQuestions',[AdminController::class,'all_questions'])->middleware(['auth','admin']);
route::get('allAnswers',[AdminController::class,'all_answers'])->middleware(['auth','admin']);
route::post('Challenge', [ChallengeController::class, 'storeChallenge']);
route::get('challenges', [ChallengeController::class, 'index'])->name('challenges');

Route::get('send-emails', [ChallengeController::class, 'showSendEmailsForm'])->name('sendEmailsForm');
Route::post('sendingEmails', [ChallengeController::class, 'sendEmailsToParticipants']);
Route::get('/statistics', [StatisticsController::class, 'showStatistics'])->name('statistics');


