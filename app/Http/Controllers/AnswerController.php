<?php

namespace App\Http\Controllers;
use Auth;
use App\Answer;
use Illuminate\Http\Request;
use App\Question;
class AnswerController extends Controller
{
    
     public function __construct() {
        
        $this->middleware('auth');
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Question $question, Request $request)
    {
        $request->validate(
                [
                    'body'=>'required'
                ]
                );
        $question->answers()->create([
            'body'=>$request->body,
            'user_id'=> Auth::id()
            
        ]);
        return back()->with('success',"Your answer has been submited successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show(Answer $answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question,Answer $answer)
    {
        $this->authorize('update',$answer);
        
        return view('answers.edit', compact('question','answer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question, Answer $answer)
    {
        $this->authorize('update',$answer);
        $answer->update($request->validate([
            'body'=>'required'
        ]));
        return redirect()->route('question.show',$question->slug)->with('success','You have successfully updated the answers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question,Answer $answer)
    {
        $this->authorize('delete',$answer);
        $answer->delete();
        return back()->with('success','Answer deleted successfully');
    }
}
