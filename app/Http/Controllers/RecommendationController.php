<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Menu;
use App\Models\Recommendation;
use GeminiService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    protected $geminiService;
    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
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

        $menu = Menu::take(10)
            ->get(['id', 'name', 'description', 'category']);
        
        
        //return response()->json($menu);        
        $userId = auth()->id();
        $userPreferences = Answer::where('user_id', $userId)
            ->with('question')
            ->get()
            ->groupBy('question_id');       
                
        $recomendations = $this->geminiService->generateRecommendations($menu, $userPreferences);
    }



}
