<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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