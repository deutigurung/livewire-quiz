<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\Quiz\QuizForm;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testAdminCanCreateQuiz()
    {
        $this->actingAs(User::factory()->admin()->create());
 
        Livewire::test(QuizForm::class)
            ->set('quiz.title', 'quiz title')
            ->call('formSubmit')
            ->assertHasNoErrors(['quiz.title', 'quiz.slug', 'quiz.description', 'quiz.published', 'quiz.public', 'questions'])
            ->assertRedirect(route('quizzes'));
 
        $this->assertDatabaseHas('quizzes', [
            'title' => 'quiz title',
        ]);
    }

    public function testTitleIsRequired() {
        $this->actingAs(User::factory()->admin()->create());
        Livewire::test(QuizForm::class)
        ->set('quiz.title', '')
        ->call('formSubmit')
        ->assertHasErrors(['quiz.title' => 'required']);
    }

    public function testAdminCanEditQuiz()
    {
        $this->actingAs(User::factory()->admin()->create());
 
        $quiz = Quiz::factory()->create();
 
        Livewire::test(QuizForm::class, [$quiz])
            ->set('quiz.title', 'new quiz')
            ->set('quiz.published', true)
            ->call('formSubmit')
            ->assertSet('quiz.published', true)
            ->assertHasNoErrors(['quiz.title', 'quiz.slug', 'quiz.description', 'quiz.published', 'quiz.public', 'questions']);
 
        $this->assertDatabaseHas('quizzes', [
            'title' => 'new quiz',
            'published' => true,
        ]);
    }
}
