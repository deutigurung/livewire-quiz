<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'question_text',
        'code_snippet',
        'answer_explanation',
        'more_info_link',
    ];

    public function questionOptions() {
        return $this->hasMany(QuestionOption::class);
    }

    public function quizzes(){
        return $this->belongsToMany(Quiz::class,'question_quiz','question_id','quiz_id');
    }
}
