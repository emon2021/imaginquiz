<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create()
    {
        $quiz = Quiz::where('status',1)->get();
        return view('user.play_games', compact('quiz'));
    }
}
