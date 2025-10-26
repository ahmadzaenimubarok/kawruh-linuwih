<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use LucianoTonet\GroqLaravel\Facades\Groq;
use App\Services\AIChatService;
use App\Models\ProjectStage;

class GroqController extends Controller
{
    protected $aiChatService;

    public function __construct(AIChatService $aiChatService)
    {
        $this->aiChatService = $aiChatService;
    }

    /**
     * List all available Groq models
     */
    public function listModels(): JsonResponse
    {
        try {
            $result = $this->aiChatService->listModels();
            
            return response()->json($result, $result['success'] ? 200 : 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test AI chat functionality
     */
    public function testChat(Request $request): JsonResponse
    {
        try {
            $message = $request->input('message', 'Hello, how are you?');
            $model = $request->input('model', config('groq.model'));
            
            $response = $this->aiChatService->chat($message, $model);
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test AI chat to generate question
     */
    public function generateQuestion(Request $request): JsonResponse
    {
        try {
            $materi = ProjectStage::find(1)->instructions;
            
            $response = $this->aiChatService->generateQuestion($materi);
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
