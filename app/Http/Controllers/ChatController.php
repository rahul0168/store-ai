<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Services\ChatService;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    private $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function index()
    {
        $conversations = Conversation::oldest()->get();
         return view('chat', compact('conversations'));
    }

    public function sendMessage(Request $request)
    {
        $message = $request->input('message');
        $user = Auth::user()->name ?? 'Guest';

        // Use the ChatService to process the message
        $response = $this->chatService->sendMessage($message, $user);

        return response()->json([
            'status' => 'success',
            'response' => $response,
        ]);
    }
}
