<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller{
    public function store(Request $request){
        $answers = $request->answers;
        
        foreach ($answers as $answer) {
            Answer::create([
                'question_id' => $answer['question_id'],
                'answer' => $answer['answer']
            ]);
        }        
        return response()->json('', 201);

    }

}