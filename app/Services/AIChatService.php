<?php

namespace App\Services;

use LucianoTonet\GroqLaravel\Facades\Groq;

class AIChatService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

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
     *
     * @return array
     * @throws \Exception
     */
    public function listModels(): array
    {
        try {
            $models = Groq::models()->list();
            
            $modelIds = [];
            foreach ($models['data'] as $model) {
                $modelIds[] = $model['id'];
            }
            
            return [
                'success' => true,
                'models' => $modelIds,
                'raw_data' => $models['data']
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send a message to AI and get response
     *
     * @param string $message
     * @param string|null $model
     * @return array
     * @throws \Exception
     */
    public function chat(string $message, ?string $model = null): array
    {
        try {
            // Validate API key
            if ($error = $this->validateApiKey()) {
                return $error;
            }

            $model = $model ?? config('groq.model');
            
            $response = Groq::chat()->completions()->create([
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $message]
                ]
            ]);
            
            return [
                'success' => true,
                'message' => $message,
                'model' => $model,
                'response' => $response['choices'][0]['message']['content'],
                'raw_response' => $response
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Clean HTML content and convert to plain text
     *
     * @param string $html
     * @return string
     */
    private function cleanHtmlContent(string $html): string
    {
        // Remove script and style tags with their content
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);
        
        // Convert common HTML entities
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Replace heading tags with line breaks and markers
        $html = preg_replace('/<h[1-6][^>]*>(.*?)<\/h[1-6]>/is', "\n\n$1\n", $html);
        
        // Replace list items with bullet points
        $html = preg_replace('/<li[^>]*>(.*?)<\/li>/is', "- $1\n", $html);
        
        // Replace paragraph and div tags with line breaks
        $html = preg_replace('/<\/?(p|div|br)[^>]*>/is', "\n", $html);
        
        // Remove all remaining HTML tags
        $html = strip_tags($html);
        
        // Clean up multiple line breaks
        $html = preg_replace('/\n{3,}/', "\n\n", $html);
        
        // Trim whitespace
        return trim($html);
    }

    /**
     * Parse questions from AI response
     *
     * @param array $aiResponse
     * @return array
     */
    public function parseQuestions(array $aiResponse): array
    {
        try {
            if (!$aiResponse['success']) {
                return [
                    'success' => false,
                    'error' => $aiResponse['error'] ?? 'Unknown error'
                ];
            }

            // Get the response content
            $content = $aiResponse['response'];
            
            // Try to decode JSON
            $questions = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [
                    'success' => false,
                    'error' => 'Failed to parse JSON: ' . json_last_error_msg()
                ];
            }

            return $questions;
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Generate question
     *
     * @param string|null $materi
     * @param string|null $model
     * @return array
     * @throws \Exception
     */
    public function generateQuestion(?string $materi = null): array
    {
        try {
            // Clean HTML content if present
            $cleanMateri = $this->cleanHtmlContent($materi);
            
            $message = <<<EOT
                Buatkan 5 pertanyaan pilihan ganda berdasarkan materi berikut: {$cleanMateri}.

                Output harus dalam format JSON array dengan struktur seperti berikut:
                [
                {
                    "question": "string",
                    "options": ["string", "string", "string", "string"],
                    "correctIndex": number,
                    "explanation": "string"
                }
                ]

                Ketentuan:
                - Pertanyaan harus relevan dengan isi materi.
                - Setiap pertanyaan harus memiliki 4 opsi jawaban.
                - Hanya satu jawaban yang benar.
                - Gunakan bahasa yang sesuai dengan konteks materi (misalnya bahasa Indonesia atau Inggris sesuai materi).
                - Jangan tambahkan penjelasan di luar array JSON.

                Gunakan format JSON valid tanpa teks tambahan di luar array.
                EOT;
            
            $response = $this->chat($message);
            
            // Parse the questions
            return $this->parseQuestions($response);
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
