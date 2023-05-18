<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    
    protected $fillable = [ 'user_id', 'quiz_id', 'result',  'ip_address','time_spent' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
 
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'test_answers', 'test_id', 'question_id');
    }
}
