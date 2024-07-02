<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Quiz;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class QuizController extends Controller
{
    //  quiz.create 
    public function create()
    {
        return view('admin.quiz.create');
    }
    //  quiz.store 
    public function store(Request $request)
    {
        $request->validate([
            'image1' => 'required|image|max:3000',
            'image2' => 'required|image|max:3000',
            'expired_time' => 'required|integer|max:9999'
        ]);

        try{
            $quiz = new \App\Models\Quiz();
            if($request->hasFile('image1') || $request->hasFile('image2'))
            {
                $image1 = $request->file('image1');
                $image2 = $request->file('image2');
                $path = "public/quiz/";
                $image1_name = uniqid().'-'.time().'.'.$image1->getClientOriginalExtension();
                $image2_name = uniqid().'-'.time().'.'.$image2->getClientOriginalExtension();

                $image1->move($path,$image1_name);
                $image2->move($path,$image2_name);

                $quiz->image1 = $path.$image1_name;
                $quiz->image2 = $path.$image2_name;
                $quiz->expired_time = $request->expired_time;
                $quiz->save();

                return response()->json('Quiz Added Successfull');
            }
        }catch(\Exception $th){
            return response()->json($th->getMessage());
        }
    }



    public function index(Request $request)
    {
        if ($request->ajax()) {
            $quiz = Quiz::all();
            return DataTables::of($quiz)
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
                ->editColumn('image1',function($data){
                    return '<img src="' . asset($data->image1) . '"  width="100px"/>';
                })
                ->editColumn('image2',function($data){
                    return '<img src="' . asset($data->image2) . '"  width="100px"/>';
                })
                ->editColumn('status',function($data){
                    if($data->status == 1)
                    {
                        return '<a href="javascript:void(0)" data-id='.$data->id.' class="btn btn-success status"> Published </a>';
                    }elseif($data->status == 2)
                    {
                        return '<a href="javascript:void(0)" data-id='.$data->id.' class="btn btn-primary status"> Unpublished </a>';
                    }
                })
                ->rawColumns(['action','image1','image2','status'])
                ->make(true);
        }
        return view('admin.quiz.index');
    }

    public function update_status(Request $request,$id)
    {
        $quiz = Quiz::find($id);
        if($quiz->status == 1)
        {
            $quiz->status = 2;
            $quiz->publish_time = null;
        }elseif($quiz->status == 2)
        {
            $quiz->status = 1;
            $quiz->publish_time = Carbon::now()->timezone('Asia/Dhaka');
        }
        
        $quiz->update();

        return response()->json('Quiz status updated!');
    }

    //  submit quiz answer
    public function answer(Request $request)
    {
        
        $request->validate([
            'quiz_id' => 'required|integer',
            'fun_name' => 'required|min:3|string',
            'single_word.*' => 'required|min:3|string',
        ]);
        
            $answer = Answer::where('quiz_id',$request->quiz_id)
                            ->where('user_id',Auth::id())->count();
            // dd($answer);
        
                $multiple_answer = [];
                foreach($request->single_word as $word)
                {
                    array_push($multiple_answer,$word);
                }
                
                $array_count = count($multiple_answer);
                if($array_count <= 5)
                {
                        if($answer <= 4)
                        {
                            foreach($request->single_word as $word)
                            {
                                Answer::create([
                                    'quiz_id' => $request->quiz_id,
                                    'user_id' => Auth::id(),
                                    'single_word' => $word,
                                    'fun_name' => $request->fun_name,
                                ]);
                                // if(in_array($word , $multiple_answer))
                                // {
                                //     $word_count = array_count_values($word);
                                //     if(exis)
                                // }
                            }
                            return response()->json(['success'=>'Answer Submitted!']);
                            
                        }else{
                            return response()->json(['attempt_error'=>'Your already submitted maximum time.']);
                        }
                    
                }else{
                    return response()->json(['limit_error'=>'You can not submit more than 5 words.']);
                }
            
        
    }
}
