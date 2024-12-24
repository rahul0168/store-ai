<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['user', 'message', 'response'];

    public function sendMessage($message)
    {
        $response = $this->client->post('chat/completions', [
            'json' => [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $message],
                ],
                'stream' => true,
            ],
        ]);

        $result = $response->getBody()->getContents();

        // Save the conversation
        Conversation::create([
            'user' => auth()->user()->name ?? 'Guest',
            'message' => $message,
            'response' => $result,
        ]);

        return $result;
    }
}
