<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminRoutesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /*test for admin can and other cannot access question page*/
    public function testAdminCanAndOthersCannotAccessQuestionsPage(){
        //for admin
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('questions'));

        $response->assertOk();
        //for users
        $user =  User::factory()->create();

        $response = $this->actingAs($user)->get(route('questions'));

        $response->assertForbidden();

        $response = $this->get(route('questions'));
        
        $response->assertForbidden();

    }
    
    /*test for admin can and other cannot access quiz page*/
    public function testAdminCanAndOthersCannotAccessQuizzesPage()
    {
        $admin = User::factory()->admin()->create();
 
        $response = $this->actingAs($admin)->get(route('quizzes'));
 
        $response->assertOk();
 
        $user = User::factory()->create();
 
        $response = $this->actingAs($user)->get(route('quizzes'));
 
        $response->assertForbidden();
 
        $response = $this->get(route('quizzes'));
 
        $response->assertForbidden();
    }
 
    /*test for admin can and other cannot access test pages*/
    public function testAdminCanAndOthersCannotAccessTestsPage()
    {
        $admin = User::factory()->admin()->create();
 
        $response = $this->actingAs($admin)->get(route('tests'));
 
        $response->assertOk();
 
        $user = User::factory()->create();
 
        $response = $this->actingAs($user)->get(route('tests'));
 
        $response->assertForbidden();
 
        $response = $this->get(route('tests'));
 
        $response->assertForbidden();
    }
}
