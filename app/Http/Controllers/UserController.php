<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
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
                    $actionbtn = '<a href="'.route('user.top_answer',$row->id).'"   class="btn btn-primary overview" >
                    Overview
                  </a>
                    <a href="javascript:void(0)"  data-id="'.$row->id.'" class="btn btn-primary edit" data-bs-target="#editModal" data-bs-toggle="modal" >
                Edit
              </a>
              <a href="#" id="delete_data" class="btn btn-danger">
              Delete
            </a>';
                    return $actionbtn;
                })
                
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->timezone('Asia/Dhaka')->format('d M Y - h:i:s A');
                })
                ->rawColumns(['action','created_at'])
                ->make(true);
        }
        return view('user.overview');
    }
    //  quiz top_answer
    public function top_answer(Request $request,$quiz_id)
    {
        if(isset($quiz_id))
        {
            if ($request->ajax()) {
                
                $answers = Answer::select('single_word',DB::raw('count(*) as count'))
                ->where('quiz_id',$quiz_id)
                ->groupBy('single_word')
                ->orderBy('count','desc')
                ->limit(10)
                ->get();
                
                
            
                return DataTables::of($answers)
                    ->addColumn('action', function ($row) {
                        $actionbtn = ' <a href="javascript:void(0)"  data-id="'.$row->id.'" class="btn btn-primary edit" data-bs-target="#editModal" data-bs-toggle="modal" >
                    Edit
                </a>
                <a href="#" id="delete_data" class="btn btn-danger">
                Delete
                </a>';
                        return $actionbtn;
                    })
                    
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('user.top_answer');
        }else{
            return back();
        }
    }
}
