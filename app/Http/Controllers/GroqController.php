<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use LucianoTonet\GroqLaravel\Facades\Groq;

class GroqController extends Controller
{
    /**
     * Check if Groq API key is configured
     */
    private function validateApiKey(): ?JsonResponse
    {
        if (empty(config('groq.api_key'))) {
            return response()->json([
                'success' => false,
                'error' => 'Groq API key is not configured. Please set GROQ_API_KEY in your .env file.'
            ], 500);
        }
        
        return null;
    }

    /**
     * List all available Groq models
     */
    public function listModels(): JsonResponse
    {
        try {
            // Validate API key
            if ($error = $this->validateApiKey()) {
                return $error;
            }

            $models = Groq::models()->list();
            
            $modelIds = [];
            foreach ($models['data'] as $model) {
                $modelIds[] = $model['id'];
            }
            
            return response()->json([
                'success' => true,
                'models' => $modelIds,
                'raw_data' => $models['data']
            ]);
            
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
            // Validate API key
            if ($error = $this->validateApiKey()) {
                return $error;
            }

            // Get message from request or use default
            $message = $request->input('message', 'Hello, how are you?');
            $model = $request->input('model', config('groq.model'));
            
            $response = Groq::chat()->completions()->create([
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $message]
                ]
            ]);
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'model' => $model,
                'response' => $response['choices'][0]['message']['content'],
                'raw_response' => $response
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
