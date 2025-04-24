<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller{
    public function store(Request $request){
        $answers = $request->answers;                
        foreach ($answers as $answer) {
            //just 1 anwser by u ser
            $existingAnswer = Answer::where('user_id', Auth::user()->id)
                ->where('question_id', $answer['question_id'])
                ->first();
            if($existingAnswer) {
                // Si ya existe una respuesta para esta pregunta, la actualizamos
                $existingAnswer->update([
                    'answer' => $answer['answer']
                ]);
                continue;
            }
            Answer::create([
                'user_id' => Auth::user()->id,
                'question_id' => $answer['question_id'],
                'answer' => $answer['answer']
            ]);
        }        
        return response()->json('Successfully', 201);

    }

}