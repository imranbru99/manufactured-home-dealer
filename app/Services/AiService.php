<?php

namespace App\Services;

use App\Models\ChatLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AiService
{
    /** @var array<int, array{q: string, a: string}> */
    protected array $faq = [
        [
            'q' => 'models in stock tour tulsa',
            'a' => 'We keep single- and multi-section models on the Tulsa lot. Book a tour to walk current inventory.',
        ],
        [
            'q' => 'price cost how much manufactured home',
            'a' => 'Pricing depends on model, options, and delivery distance. Prairieform quotes after a lot consult.',
        ],
        [
            'q' => 'delivery setup blocking land',
            'a' => 'Delivery and setup packages include transport coordination and blocking — scoped to your site.',
        ],
        [
            'q' => 'finance lender paperwork',
            'a' => 'We help assemble serials, invoices, and setup scopes lenders commonly request.',
        ],
        [
            'q' => 'broken arrow owasso service area',
            'a' => 'Prairieform serves Tulsa, Broken Arrow, Owasso, and nearby Northeast Oklahoma.',
        ],
        [
            'q' => 'warranty after purchase',
            'a' => 'Warranty handoff walks systems and claim paths for new owners.',
        ],
        [
            'q' => 'deposit hold inventory',
            'a' => 'Deposits lock specific inventory in the ERP so the home you buy is the home that ships.',
        ],
        [
            'q' => 'multi section vs single',
            'a' => 'Multi-section homes offer wider living areas; singles can be easier on tight land. Tours compare both.',
        ],
    ];

    public function chat(string $message, ?string $sessionId = null): array
    {
        $reply = null;
        $source = 'demo';

        if ($key = config('services.openai.key')) {
            try {
                $reply = $this->callOpenAi($message, $key);
                $source = 'openai';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply && ($gemini = config('services.gemini.key'))) {
            try {
                $reply = $this->callGemini($message, $gemini);
                $source = 'gemini';
            } catch (\Throwable) {
                $reply = null;
            }
        }

        if (! $reply) {
            $reply = $this->demoReply($message);
            $source = 'demo';
        }

        ChatLog::query()->create([
            'session_id' => $sessionId,
            'message' => $message,
            'reply' => $reply,
            'source' => $source,
        ]);

        return ['reply' => $reply, 'source' => $source];
    }

    public function generate(string $type, string $prompt): string
    {
        $message = match ($type) {
            'blog' => "Write a concise SEO blog post outline and intro for a fire damage restoration company about: {$prompt}",
            'description' => "Write a compelling service description for fire and smoke restoration: {$prompt}",
            'automation' => "Suggest 5 practical AI automations for a fire damage restoration SME ERP related to: {$prompt}",
            'troubleshoot' => "Troubleshoot this fire restoration operations issue with clear steps: {$prompt}",
            default => $prompt,
        };

        $result = $this->chat($message);

        return $result['reply'];
    }

    protected function demoReply(string $message): string
    {
        $hay = Str::lower($message);
        $best = null;
        $bestScore = 0;

        foreach ($this->faq as $item) {
            $score = 0;
            foreach (explode(' ', $item['q']) as $word) {
                if ($word !== '' && str_contains($hay, $word)) {
                    $score++;
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $item['a'];
            }
        }

        if ($best && $bestScore > 0) {
            return $best;
        }

        return 'Thanks for reaching Prairieform Homes. We sell and set manufactured homes across Tulsa and Northeast Oklahoma. Ask about models, delivery, or booking a lot tour — or call (918) 555-0183.';
    }

    protected function callOpenAi(string $message, string $key): string
    {
        $response = Http::withToken($key)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are the Prairieform Homes assistant for a Tulsa, OK manufactured home dealer. Be concise about inventory, delivery, setup, and paperwork. Never invent illegal claims.',
                    ],
                    ['role' => 'user', 'content' => $message],
                ],
                'temperature' => 0.4,
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'choices.0.message.content', 'Unable to generate a response.');
    }

    protected function callGemini(string $message, string $key): string
    {
        $model = config('services.gemini.model', 'gemini-1.5-flash');
        $response = Http::timeout(30)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "You are the Prairieform Homes assistant for a Tulsa, OK manufactured home dealer. Be concise about inventory, delivery, setup, and paperwork. Never invent illegal claims.
{$message}"],
                        ],
                    ],
                ],
            ]);

        $response->throw();

        return (string) data_get($response->json(), 'candidates.0.content.parts.0.text', 'Unable to generate a response.');
    }
}
