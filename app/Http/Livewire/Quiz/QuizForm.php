<?php

namespace App\Http\Livewire\Quiz;

use App\Models\Question;
use App\Models\Quiz;
use Livewire\Component;
use Illuminate\Support\Str;

use function PHPUnit\Framework\isEmpty;

class QuizForm extends Component
{
    public Quiz $quiz;
    public bool $editing = false;
    public array $questions = [];
    public array $listsForFields;

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
        $this->initialListsForFields();
        if($this->quiz->exists){
            $this->editing = true;
            //render question in edit page
            $this->questions = !isEmpty($this->quiz->questions()) ? $this->quiz->questions()->pluck('id')->toArray() : [];
        }else{
            $this->quiz->published = false;
            $this->quiz->public = false;
        }
    }

    public function formSubmit()
    {
        $this->validate();
        $this->quiz->save();
        $this->quiz->questions()->sync($this->questions);
        return to_route('quizzes');
    }

    // updatedQuizTitle is livewire lifecycle hook 
    // which runs after updating a nested property title on the $quiz property 
    // similar to updatedFooBar 
    public function updatedQuizTitle(){
        $this->quiz->slug = Str::slug($this->quiz->title);
    }

    protected function initialListsForFields(){
        $this->listsForFields['questions'] = Question::pluck('question_text','id')->toArray();
    }
}
