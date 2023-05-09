<?php

namespace App\Http\Livewire\Quiz;

use App\Models\Quiz;
use Livewire\Component;
use Illuminate\Support\Str;

class QuizForm extends Component
{
    public Quiz $quiz;
    public bool $editing = false;

    protected function rules() {
        return [
            'quiz.title'=> ['required','string'],
            'quiz.slug' => ['required','string'],
            'quiz.description' => ['nullable','string'],
            'quiz.published' => ['boolean'],
            'quiz.public' => ['boolean'],
        ];
    }
    public function render()
    {
        return view('livewire.quiz.form');
    }

    public function mount(Quiz $quiz){
        $this->quiz = $quiz;
        if($this->quiz->exists){
            $this->editing = true;
        }else{
            $this->quiz->published = false;
            $this->quiz->public = false;

        }
    }

    public function formSubmit()
    {
        $this->validate();
        $this->quiz->save();
        return to_route('quizzes');
    }

    // updatedQuizTitle is livewire lifecycle hook 
    // which runs after updating a nested property title on the $quiz property 
    // similar to updatedFooBar 
    public function updatedQuizTitle(){
        $this->quiz->slug = Str::slug($this->quiz->title);
    }
}
