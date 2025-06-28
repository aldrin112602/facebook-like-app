<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            ->get();

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
            'content' => 'nullable|string|max:5000',
            'file' => 'nullable|file|max:20480', // 20MB max
            'type' => 'in:text,image,video,file',
        ]);

        $user = Auth::user();

        // Check if they are friends
        if (!$user->isFriendWith($request->recipient_id)) {
            return back()->with('error', 'You can only message friends!');
        }

        $messageData = [
            'sender_id' => $user->id,
            'recipient_id' => $request->recipient_id,
            'type' => 'text',
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $mimeType = $file->getMimeType();
            
            // Determine message type based on MIME type
            if (str_starts_with($mimeType, 'image/')) {
                $messageData['type'] = 'image';
            } elseif (str_starts_with($mimeType, 'video/')) {
                $messageData['type'] = 'video';
            } else {
                $messageData['type'] = 'file';
            }

            // Store file
            $path = $file->store('messages', 'public');
            $messageData['file_path'] = $path;
            $messageData['file_name'] = $file->getClientOriginalName();
            $messageData['file_size'] = $file->getSize();
            $messageData['content'] = $request->content ?? $file->getClientOriginalName();
        } else {
            // Text message
            $messageData['content'] = $request->content;
        }

        // Validate that either content or file is provided
        if (empty($messageData['content']) && !isset($messageData['file_path'])) {
            return back()->with('error', 'Please provide either a message or attach a file.');
        }

        Message::create($messageData);

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

    public function downloadFile($messageId)
    {
        $user = Auth::user();
        
        $message = Message::where('id', $messageId)
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('recipient_id', $user->id);
            })
            ->whereNotNull('file_path')
            ->firstOrFail();

        if (!Storage::disk('public')->exists($message->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($message->file_path, $message->file_name);
    }
}