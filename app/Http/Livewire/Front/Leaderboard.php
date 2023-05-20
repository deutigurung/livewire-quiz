<?php

namespace App\Http\Livewire\Front;

use App\Models\QuestionQuiz;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Leaderboard extends Component
{
    public Collection $quizzes;

    public int $quizId = 0;

    public function mount(){
        $this->quizzes = Quiz::where('public',1)->where('published',1)->get();
    }
    public function render()
    {
        $total_questions = QuestionQuiz::select('question_id')
                        ->join('quizzes', 'question_quiz.quiz_id', '=', 'quizzes.id')
                        ->where('quizzes.published', 1)
                        ->when($this->quizId > 0,function($q){
                            return $q->where('quiz_id',$this->quizId);
                        })
                        ->count();

        $users = User::selectRaw('users.name,SUM(tests.result) as correct,SUM(tests.time_spent) as time_spent')
                    ->join('tests', 'users.id', '=', 'tests.user_id')
                    ->whereNotNull('tests.quiz_id')
                    ->whereNotNull('tests.time_spent')
                    ->when($this->quizId > 0,function($q){
                        return $q->where('tests.quiz_id',$this->quizId);
                    })
                    ->groupBy('users.id', 'users.name')
                    ->orderBy('correct', 'desc')
                    ->orderBy('time_spent')
                    ->get();
        return view('livewire.front.leaderboard',compact('users','total_questions'));
    }
}
