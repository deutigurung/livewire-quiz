<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionOption extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['options','question_id','correct'];

    protected $casts = ['correct'=>'boolean'];

    public function question(){
        return $this->belongsTo(Question::class);
    }
}
