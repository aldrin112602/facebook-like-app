<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $conversationPartners = collect();
        $sentMessages = Message::where('sender_id', $user->id)
            ->distinct()
            ->pluck('recipient_id');

        // Get all messages where user is recipient  
        $receivedMessages = Message::where('recipient_id', $user->id)
            ->distinct()
            ->pluck('sender_id');

        // Combine and get unique partner IDs
        $partnerIds = $sentMessages->merge($receivedMessages)->unique();


        $conversations = collect();

        foreach ($partnerIds as $partnerId) {
            // Get the partner user
            $partner = User::find($partnerId);


            // Get the latest message between these two users
            $latestMessage = Message::betweenUsers($user->id, $partnerId)
                ->orderBy('created_at', 'desc')
                ->first();



            // Get unread messages count (messages from partner to user that are unread)
            $unreadCount = Message::where('sender_id', $partnerId)
                ->where('recipient_id', $user->id)
                ->where('is_read', false)
                ->count();


            $conversations->push([
                'friend' => $partner,
                'latest_message' => $latestMessage,
                'unread_count' => $unreadCount,
            ]);
        }

        // Sort conversations by latest message created_at (most recent first)
        $conversations = $conversations->sortByDesc(function ($conversation) {
            return $conversation['latest_message']->created_at;
        });



        return view('messages.index', compact('conversations'));
    }

    public function show($userId)
    {
        $user = Auth::user();
        $friend = User::findOrFail($userId);

        // Check if they are friends
        if (!$user->isFriendWith($userId)) {
            return redirect()->route('friends.index')->with('error', 'You can only message friends!');
        }

        // Get messages between users
        $messages = Message::betweenUsers($user->id, $userId)
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        // Mark messages as read
        Message::where('sender_id', $userId)
            ->where('recipient_id', $user->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return view('messages.show', compact('messages', 'friend'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id|different:' . Auth::id(),
            'content' => 'required|string|max:5000',
            'type' => 'in:text,image,file',
        ]);

        $user = Auth::user();

        // Check if they are friends
        if (!$user->isFriendWith($request->recipient_id)) {
            return back()->with('error', 'You can only message friends!');
        }

        Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $request->recipient_id,
            'content' => $request->content,
            'type' => $request->type ?? 'text',
        ]);

        return back()->with('success', 'Message sent successfully!');
    }

    public function markAsRead($messageId)
    {
        $user = Auth::user();

        $message = Message::where('id', $messageId)
            ->where('recipient_id', $user->id)
            ->firstOrFail();

        $message->markAsRead();

        return response()->json(['success' => true]);
    }
}
