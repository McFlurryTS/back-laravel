<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        $questions = Question::all();
        return response()->json($questions);
    }

  
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array',
            'type' => 'required|in:multiple_choice,single_choice,text'
        ]);

        $question = Question::create($validated);
        return response()->json($question, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        return response()->json($question);
    }

  
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'question' => 'sometimes|string|max:255',
            'options' => 'sometimes|array',
            'type' => 'sometimes|in:multiple_choice,single_choice,text'
        ]);

        $question->update($validated);
        return response()->json($question);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return response()->json(null, 204);
    }
}
