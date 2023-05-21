<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Questions\QuestionForm;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class QuestionsTest extends TestCase
{
    /**
     * A basic feature test example.
     */

     public function testAdminCanCreateQuestion()
     {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin);
  
        /* test QuestionForm livewire component if the form has no errors 
        and creates and updates the question 
        here, question is the public varibable defined inside component 
        and call submitFormData() to store*/
         Livewire::test(QuestionForm::class)
             ->set('question.question_text', 'demo question')
             ->set('question.answer_explanation', 'answer explanation')
             ->set('question.more_info_link', 'http://localhost/')
             ->set('questionOptions.0.option', 'answer 1')
             ->call('submitFormData')
             ->assertHasNoErrors(['question.question_text', 'question.code_snippet', 'question.answer_explanation', 'question.more_info_link', 'question.topic_id', 'questionOptions', 'questionOptions.*.option'])
             ->assertRedirect(route('questions'));
  
         $this->assertDatabaseHas('questions', [
             'question_text' => 'demo question',
             'more_info_link' => 'http://localhost/',
             'answer_explanation' => 'answer explanation'
         ]);
     }

     public function testQuestionTextIsRequired()
     {
         $this->actingAs(User::factory()->admin()->create());
  
         Livewire::test(QuestionForm::class)
             ->set('question.question_text', '')
             ->call('submitFormData')
             ->assertHasErrors(['question.question_text' => 'required']);
  
         Livewire::test(QuestionForm::class)
             ->set('question.question_text', '')
             ->call('submitFormData')
             ->assertHasErrors(['question.question_text' => 'required']);
     }

     public function testAdminCanEditQuestion()
     {
         $this->actingAs(User::factory()->admin()->create());
  
         $question = Question::factory()->has(QuestionOption::factory())->create();
  
         Livewire::test(QuestionForm::class, [$question])
            ->set('question.question_text', 'demo question')
            ->set('question.answer_explanation', 'answer explanation')
            ->set('question.more_info_link', 'http://localhost/')
            ->set('questionOptions.0.option', 'answer 1')
            ->call('submitFormData')
            ->assertHasNoErrors(['question.question_text', 'question.code_snippet', 'question.answer_explanation', 'question.more_info_link', 'question.topic_id', 'questionOptions', 'questionOptions.*.option'])
            ->assertRedirect(route('questions'));
  
         $this->assertDatabaseHas('questions', [
            'question_text' => 'demo question',
             'more_info_link' => 'http://localhost/',
             'answer_explanation' => 'answer explanation'
         ]);
     }
}
