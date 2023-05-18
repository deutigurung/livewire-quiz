<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    use HasFactory;

    protected $fillable = [  'user_id',   'test_id','question_id', 'option_id', 'correct' ];
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    public function test()
    {
        return $this->belongsTo(Test::class);
    }
 
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
 
    public function option()
    {
        return $this->belongsTo(QuestionOption::class);
    }
}
