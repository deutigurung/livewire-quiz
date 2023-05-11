<?php

namespace App\Http\Livewire\Quiz;

use App\Models\Quiz;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class QuizList extends Component
{
    public function render()
    {
        $quizzes = Quiz::withCount('questions')->latest()->paginate();
        return view('livewire.quiz.list',compact('quizzes'));
    }

    public function destroy(Quiz $quiz){
        abort_if(!auth()->user()->is_admin, Response::HTTP_FORBIDDEN,'403 Forbidden');
        $quiz->delete();
    }
}
