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
        
        $menu = Menu::all(['id', 'name', 'description', 'category']);

        //Get 10 items for each category        
        //$menu = Menu::select(['id', 'name', 'description', 'category'])->get()->groupBy('category')->map(function ($items) {
          //  return $items->take(10);
        //})->flatten(1);
        //return response()->json($menu);        
        $userId = auth()->id();
        $userPreferences = Answer::where('user_id', $userId)
            ->with('question:id,question')
            ->get();      
        if($userPreferences->isEmpty()){
            return response()->json(['error' => 'No se encontraron preferencias de usuario'], 404);
        }
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
        $recommendations = json_decode($this->openAIService->generateRecommendations($menu->toArray(), $preferences)); 
        foreach($recommendations as $r){
            foreach($r->options as &$option){
                $menuItem = Menu::where('id', $option->id)->first(['id', 'name', 'description', 'category', 'price', 'image']);
                if($menuItem){                    
                    $option = $menuItem;
                }
            }
        } 
          // $recommendations is already an array or null, no need to decode
        if ($recommendations) {
            return response()->json($recommendations, 200);
        } else {
            return response()->json(['error' => 'No se pudieron obtener las recomendaciones de OpenAI'], 500);
        }
                
    }
    public function getComboRecommendation()
    {
        
        //$menu = Menu::all(['id', 'name', 'description', 'category']);

        //Get 10 items for each category        
        $menu = Menu::select(['id', 'name', 'description', 'category'])
            ->get()
            ->groupBy('category')
            ->map(function ($items) {
            return $items->take(10);
            })
            ->flatten(1);
        //return response()->json($menu);        
        $userId = auth()->id();
        $userPreferences = Answer::where('user_id', $userId)
            ->with('question:id,question')
            ->get();                             
                            
        $preferences = [];
        foreach($userPreferences as $p){
            array_push($preferences, [
                'question' => $p->question->question,
                'answer' => $p->answer
            ]);
        }
        
        $recommendations = $this->openAIService->generateCombosRecommendations($menu->toArray(), $preferences); 
             
        
        if ($recommendations) {
            return response()->json(json_decode($recommendations), 200);
        } else {
            return response()->json(['error' => 'No se pudieron obtener las recomendaciones de OpenAI'], 500);
        }
                
    }


}
