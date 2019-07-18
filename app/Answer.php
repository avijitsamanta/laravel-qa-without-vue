<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Question;

use App\User;

class Answer extends Model
{
    use VotableTrait;
    protected $fillable = ['body','user_id'];
    public function question() {
        
       return  $this->belongsTo(Question::class);
    }
    
    public function user() {
        
       return $this->belongsTo(User::class);
    }
    
    public function getBodyHtmlAttribute()
    {
        return \Parsedown::instance()->text($this->body);
    }
    
    public static function boot() {
        parent::boot();

        static::created(function ($answer) {
            $answer->question->increment('answers_count');
        });
        
        static::deleted(function ($answer){
            $answer->question->decrement('answers_count');
            if($answer->id === $answer->question->best_answer_id)
            {
                $answer->question->best_answer_id = NULL;
                $answer->question->save();
            }
        });
    }
    
    public function getCreatedDateAttribute() {
     return $this->created_at->diffForHumans();   
    }
    
    public function  getStatusAttribute(){
        
        return $this->id === $this->question->best_answer_id ? 'vote-accepted':'';
    }

}
