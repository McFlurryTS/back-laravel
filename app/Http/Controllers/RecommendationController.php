<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Menu;
use App\Models\Recommendation;
use App\Services\OpenAIService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{    
    protected $openAIService;

    public function __construct()
    {        
        $this->openAIService = new OpenAIService();
    }

    public function index()
    {
        return Recommendation::with('user', 'menu')
            ->where('user_id', auth()->id())
            ->get();
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        return Recommendation::create($validated);
    }
    public function getRecommendation()
    {
        //$menu = Menu::take(10)->get(['id', 'name', 'description', 'category']);                
        $menu = Menu::all(['id', 'name', 'description', 'category']);
        //return response()->json($menu);        
        $userId = auth()->id();
        $userPreferences = Answer::where('user_id', $userId)
            ->with('question:id,question')
            ->get();                             
        //return response()->json($userPreferences); 
        //{"question" : 'que prefieres', "answer" : "McNuggets"},
        $preferences = [];
        foreach($userPreferences as $p){
            array_push($preferences, [
                'question' => $p->question->question,
                'answer' => $p->answer
            ]);
        }
        //return response()->json($preferences);
                                      
        $recommendations = $this->openAIService->generateRecommendations($menu->toArray(), $preferences);
        
        if ($recommendations) {
            return response()->json(['recommendations' => $recommendations]);
        } else {
            return response()->json(['error' => 'No se pudieron obtener las recomendaciones de OpenAI'], 500);
        }
                
    }



}
