<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// })->name('login');

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('is_user');


//  Admin Routes
Route::get('/admin/home',[AdminController::class,'index'])->name('admin.home')->middleware('is_admin');

Route::controller(App\Http\Controllers\QuizController::class)->middleware(['auth','is_admin'])->group(function(){
    Route::get('/admin/quiz','create')->name('admin.quiz.create');
    Route::post('/admin/quiz/store','store')->name('admin.quiz.store');
    Route::get('/admin/quiz/view','index')->name('admin.quiz.index');
    Route::get('/admin/quiz/status/{id}','update_status');
   
});
Route::post('/quiz/answer', [QuizController::class,'answer'])->name('quiz.answer');
//  User Routes 
Route::controller(App\Http\Controllers\UserController::class)->middleware(['auth'])->group(function(){
    Route::get('/user/quiz/game','create')->name('user.game');
});