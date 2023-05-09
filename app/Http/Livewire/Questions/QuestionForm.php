<?php

namespace App\Http\Livewire\Questions;

use Livewire\Component;
use App\Models\Question;

class QuestionForm extends Component
{
    public Question $question;
    public bool $editing = false;

    public function mount(Question $question){
        $this->question = $question;
        if($this->question->exists){
            $this->editing = true;
        }
    }
    public function render()
    {
        return view('livewire.questions.question-form');
    }

    public function submitFormData(){
        $this->validate();
        $this->question->save();
        return to_route('questions');
    }

    protected function rules() {
        return [
            'question.question_text'=> ['string','required'],
            'question.code_snippet' => ['string','nullable'],
            'question.answer_explanation' => ['string','nullable'],
            'question.more_info_link' => ['url','nullable'],
        ];
    }
}
