<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\QuizController;

class UserController extends Controller
{
    public function create()
    {
        $quiz = Quiz::where('status',1)->get();
        return view('user.play_games', compact('quiz'));
    }

    //  quiz overview
    public function overview(Request $request)
    {
        if ($request->ajax()) {
            $id = Auth::id();
            $answers = Answer::leftJoin('quizzes','quizzes.id', '=' , 'answers.quiz_id')
                        ->select('quizzes.*')
                        ->where('answers.user_id',$id)
                        ->distinct()
                        ->get();
          
            return DataTables::of($answers)
                ->addColumn('action', function ($row) {
                    $actionbtn = '<a href="javascript:void(0)"  data-id="'.$row->id.'" class="btn btn-primary edit" data-bs-target="#editModal" data-bs-toggle="modal" >
                Edit
              </a>
              <a href="#" id="delete_data" class="btn btn-danger">
              Delete
            </a>';
                    return $actionbtn;
                })
                
                ->addIndexColumn()
                // ->editColumn('publish_time', function ($row) {
                //     return date_format($row->publish_time,'d M Y - h:m');
                // })
                ->rawColumns(['action','publish_time'])
                ->make(true);
        }
        return view('user.overview');
    }
}
