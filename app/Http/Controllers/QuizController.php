<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Yajra\DataTables\DataTables;

use Illuminate\Http\Request;

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
                        return "<a>Published</a>";
                    }elseif($data->status == 2)
                    {
                        return '<a href="#" class="btn btn-success"> Unpublished </a>';
                    }
                })
                ->rawColumns(['action','image1','image2','status'])
                ->make(true);
        }
        return view('admin.quiz.index');
    }
}
