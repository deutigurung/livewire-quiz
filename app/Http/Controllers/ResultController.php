<?php

namespace App\Http\Controllers;

use App\Models\TestAnswer;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;

class ResultController extends Controller
{

    public function index(){
        $results = Test::with('quiz')->withCount('questions')
                    ->where('user_id', auth()->id())->paginate();
        return view('front.results.index', compact('results'));
    }
    public function show(Test $test){
        $test->load('user','quiz');
        $users = null;
        $total_questions = $test->quiz->questions->count();
        $results = TestAnswer::where('test_id',$test->id)->with('question.questionOptions')->get();

        if($test->quiz->public == 0){
            $users = User::selectRaw('SUM(tests.time_spent) AS time_spent, SUM(tests.result) AS correct , users.id, users.name')
            ->join('tests','users.id','=','tests.user_id')
            ->where('tests.quiz_id',$test->quiz_id)
            ->whereNotNull('tests.time_spent')
            ->groupBy('users.id','users.name')
            ->orderBy('correct','desc')
            ->orderBy('tests.time_spent')
            ->get();
        }

        return view('front.results.show',compact('test','results','total_questions','users'));
    }
}
