<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get accepted friends with their latest activity
        $friendIds = Friend::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('friend_id', $user->id);
        })
            ->where('status', Friend::STATUS_ACCEPTED)
            ->get()
            ->map(function ($friendship) use ($user) {
                return $friendship->user_id == $user->id ? $friendship->friend_id : $friendship->user_id;
            });

        $friends = User::whereIn('id', $friendIds)->get();

        // Get pending friend requests received
        $pendingRequests = Friend::where('friend_id', $user->id)
            ->where('status', Friend::STATUS_PENDING)
            ->with('user')
            ->get();

        // Get sent friend requests
        $sentRequests = Friend::where('user_id', $user->id)
            ->where('status', Friend::STATUS_PENDING)
            ->with('friend')
            ->get();

        // Get suggested friends (users who are not friends and no pending requests)
        $excludeIds = collect([$user->id])
            ->merge($friends->pluck('id'))
            ->merge($pendingRequests->pluck('user_id'))
            ->merge($sentRequests->pluck('friend_id'))
            ->unique()
            ->values();

        $suggestedFriends = User::whereNotIn('id', $excludeIds)
            ->limit(10)
            ->get();

        return view('friends.index', compact('friends', 'pendingRequests', 'sentRequests', 'suggestedFriends'));
    }

    public function sendRequest(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id|different:' . Auth::id(),
        ]);

        $user = Auth::user();
        $friendId = $request->friend_id;

        // Check if any relationship already exists
        $existingFriendship = Friend::where(function ($query) use ($user, $friendId) {
            $query->where('user_id', $user->id)->where('friend_id', $friendId);
        })->orWhere(function ($query) use ($user, $friendId) {
            $query->where('user_id', $friendId)->where('friend_id', $user->id);
        })->first();

        if ($existingFriendship) {
            if ($existingFriendship->status === Friend::STATUS_ACCEPTED) {
                return back()->with('error', 'You are already friends!');
            } elseif ($existingFriendship->status === Friend::STATUS_PENDING) {
                return back()->with('error', 'Friend request already exists!');
            }
        }

        Friend::create([
            'user_id' => $user->id,
            'friend_id' => $friendId,
            'status' => Friend::STATUS_PENDING,
        ]);

        return back()->with('success', 'Friend request sent successfully!');
    }

    public function acceptRequest($id)
    {
        $user = Auth::user();

        $friendRequest = Friend::where('id', $id)
            ->where('friend_id', $user->id)
            ->where('status', Friend::STATUS_PENDING)
            ->firstOrFail();

        $friendRequest->update(['status' => Friend::STATUS_ACCEPTED]);

        return back()->with('success', 'Friend request accepted!');
    }

    public function declineRequest($id)
    {
        $user = Auth::user();

        $friendRequest = Friend::where('id', $id)
            ->where('friend_id', $user->id)
            ->where('status', Friend::STATUS_PENDING)
            ->firstOrFail();

        $friendRequest->delete();

        return back()->with('success', 'Friend request declined!');
    }

    public function remove($friendId)
    {
        $user = Auth::user();

        // Remove the friendship record
        Friend::where(function ($query) use ($user, $friendId) {
            $query->where('user_id', $user->id)->where('friend_id', $friendId);
        })->orWhere(function ($query) use ($user, $friendId) {
            $query->where('user_id', $friendId)->where('friend_id', $user->id);
        })->delete();

        return back()->with('success', 'Friend removed successfully!');
    }
}
