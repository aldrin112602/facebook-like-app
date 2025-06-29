<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;
use App\Models\User;
use App\Events\VideoCallOffer;
use App\Events\VideoCallAnswer;
use App\Events\VideoCallCandidate;
use App\Events\VideoCallEnd;

class VideoCallController extends Controller
{
    public function initiate(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id'
        ]);

        $friend = User::findOrFail($request->friend_id);
        $caller = auth()->user();

        // Generate unique call ID
        $callId = uniqid('call_');

        // Send call offer to friend via Pusher
        event(new VideoCallOffer([
            'call_id' => $callId,
            'caller' => [
                'id' => $caller->id,
                'name' => $caller->name,
                'avatar' => $caller->avatar
            ],
            'receiver_id' => $friend->id
        ]));

        return response()->json([
            'success' => true,
            'call_id' => $callId,
            'message' => 'Call initiated'
        ]);
    }

    public function answer(Request $request)
    {
        $request->validate([
            'call_id' => 'required|string',
            'caller_id' => 'required|exists:users,id',
            'accepted' => 'required|boolean'
        ]);

        $caller = User::findOrFail($request->caller_id);
        $receiver = auth()->user();

        if ($request->accepted) {
            // Send answer to caller
            event(new VideoCallAnswer([
                'call_id' => $request->call_id,
                'accepted' => true,
                'receiver' => [
                    'id' => $receiver->id,
                    'name' => $receiver->name,
                    'avatar' => $receiver->avatar
                ],
                'caller_id' => $caller->id
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Call accepted',
                'redirect' => route('video.call', ['call_id' => $request->call_id])
            ]);
        } else {
            // Reject call
            event(new VideoCallEnd([
                'call_id' => $request->call_id,
                'reason' => 'rejected',
                'caller_id' => $caller->id
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Call rejected'
            ]);
        }
    }

    public function show($callId)
    {
        return view('video-call.index', compact('callId'));
    }

    public function sendCandidate(Request $request)
    {
        $request->validate([
            'call_id' => 'required|string',
            'candidate' => 'required|array',
            'target_user_id' => 'required|exists:users,id'
        ]);

        event(new VideoCallCandidate([
            'call_id' => $request->call_id,
            'candidate' => $request->candidate,
            'target_user_id' => $request->target_user_id
        ]));

        return response()->json(['success' => true]);
    }

    public function sendOffer(Request $request)
    {
        $request->validate([
            'call_id' => 'required|string',
            'offer' => 'required|array',
            'target_user_id' => 'required|exists:users,id'
        ]);

        broadcast(new VideoCallOffer([
            'call_id' => $request->call_id,
            'offer' => $request->offer,
            'target_user_id' => $request->target_user_id,
            'from_user_id' => auth()->id()
        ]));

        return response()->json(['success' => true]);
    }

    public function sendAnswer(Request $request)
    {
        $request->validate([
            'call_id' => 'required|string',
            'answer' => 'required|array',
            'target_user_id' => 'required|exists:users,id'
        ]);

        broadcast(new VideoCallAnswer([
            'call_id' => $request->call_id,
            'answer' => $request->answer,
            'target_user_id' => $request->target_user_id,
            'from_user_id' => auth()->id()
        ]));

        return response()->json(['success' => true]);
    }

    public function endCall(Request $request)
    {
        $request->validate([
            'call_id' => 'required|string',
            'target_user_id' => 'required|exists:users,id'
        ]);

        event(new VideoCallEnd([
            'call_id' => $request->call_id,
            'reason' => 'ended',
            'target_user_id' => $request->target_user_id,
            'from_user_id' => auth()->id()
        ]));

        return response()->json(['success' => true]);
    }
}