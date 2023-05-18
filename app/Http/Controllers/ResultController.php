<?php

namespace App\Http\Controllers;

use App\Models\TestAnswer;
use App\Models\Test;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function show(Test $test){
        $total_questions = $test->quiz->questions->count();
        $results = TestAnswer::where('test_id',$test->id)->with('question.questionOptions')->get();
        return view('front.results.show',compact('test','results','total_questions'));
    }
}
