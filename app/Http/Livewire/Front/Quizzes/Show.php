<?php

namespace App\Http\Livewire\Front\Quizzes;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\TestAnswer;
use App\Models\Test;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

use Livewire\Component;

class Show extends Component
{
    public Quiz $quiz; //current taken quiz
    public Collection $questions; //collection of quiz
    public Question $currentQuestion; //current question that need to be answered on screen
    public int $currentQuestionIndex = 0; //key num of which question to display
    public int $startTimeSeconds  = 0; 
    public array $questionsAnswers = []; //users selected answers

    public function mount()
    {
        $this->startTimeSeconds = now()->timestamp;

        $this->questions = Question::query()->inRandomOrder()
                    ->whereRelation('quizzes','quizzes.id',$this->quiz->id)
                    ->with('questionOptions')->get(); 
        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
        //$this->questionsCount is computed properties
        for($i=0;$i < $this->questionsCount; $i++){
            $this->questionsAnswers[$i] = [];
        }
    }

    public function render() : View
    {
        return view('livewire.front.quizzes.show');
    }

    public function getQuestionsCountProperty() :int {
        // livewire computed properties which gets cached and we can reuse its value.
        return $this->questions->count(); 
    }

    public function changeQuestion(){
        $this->currentQuestionIndex++;
        //if currentQuestionIndex is equal or qreater to question,we call submitAnswer method
        if($this->currentQuestionIndex >= $this->questionsCount){
            return $this->submitAnswer();
        }
        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
        // dd($this->currentQuestion);
    }

    public function submitAnswer() {
        $result = 0;
        $test = Test::create([
            'user_id' => auth()->id(),
            'quiz_id' => $this->quiz->id,
            'result' => 0,
            'ip_address' => request()->ip(),
            'time_spent' => now()->timestamp - $this->startTimeSeconds
        ]);
        foreach($this->questionsAnswers as $key => $answer){
            $status = 0;
            if(!empty($answer) && QuestionOption::find($answer)->correct){
                $status = 1; $result++;
            }
            TestAnswer::create([
                'user_id' => auth()->id(),
                'test_id' => $test->id,
                'question_id' => $this->questions[$key]->id,
                'option_id' => $answer ?? null,
                'correct' => $status,
            ]);
        }
        $test->update(['result'=>$result]);
        return to_route('results.show',$test);
    }
}

