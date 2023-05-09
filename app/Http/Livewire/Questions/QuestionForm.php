<?php

namespace App\Http\Livewire\Questions;

use Livewire\Component;
use App\Models\Question;

class QuestionForm extends Component
{
    public Question $question;
    public bool $editing = false;

    public array $questionOptions = [];

    public function mount(Question $question){
        $this->question = $question;
        if($this->question->exists){
            $this->editing = true;
            foreach($this->question->questionOptions as $options){
                $this->questionOptions[] = [
                    'id' => $options->id,
                    'option' => $options->option,
                    'correct' => $options->correct,
                ];
            }
        }
    }
    public function render()
    {
        return view('livewire.questions.question-form');
    }

    public function submitFormData(){
        $this->validate();
        $this->question->save();
        //remove question options
        $this->question->questionOptions()->delete();

        foreach($this->questionOptions as $option){
            //create question multiple options
            $this->question->questionOptions()->create($option);
        }
        return to_route('questions');
    }

    protected function rules() {
        return [
            'question.question_text'=> ['required','string'],
            'question.code_snippet' => ['nullable','string'],
            'question.answer_explanation' => ['required','string'],
            'question.more_info_link' => ['required','url'],
            'questionOptions' => ['required','array'],
            'questionOptions.*.option' => ['required','string'],
        ];
    }

    public function addQuestionsOption() {
        $this->questionOptions[] = [
            'option' => '', 
            'correct' => false
        ];
    }

    public function removeQuestionsOption(int $index) {
        //remove current index and update array
        unset($this->questionOptions[$index]);
        $this->questionOptions = array_values($this->questionOptions);
    }
}
