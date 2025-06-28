<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all friend IDs in both directions
        $friendIds = collect();

        // Get friends where current user sent the request (accepted)
        $sentFriends = \DB::table('friends')
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->pluck('friend_id');

        // Get friends where current user received the request (accepted)
        $receivedFriends = \DB::table('friends')
            ->where('friend_id', $user->id)
            ->where('status', 'accepted')
            ->pluck('user_id');

        $friendIds = $sentFriends->merge($receivedFriends)->unique();

        $posts = Post::with(['user', 'likes.user', 'comments.user'])
            ->where(function ($query) use ($user, $friendIds) {
                // Public posts - visible to everyone
                $query->where('privacy', 'public')
                    // OR private posts owned by the current user
                    ->orWhere(function ($query) use ($user) {
                        $query->where('privacy', 'private')
                            ->where('user_id', $user->id);
                    })
                    // OR friends posts where the author is a friend
                    ->orWhere(function ($query) use ($friendIds) {
                        $query->where('privacy', 'friends')
                            ->whereIn('user_id', $friendIds);
                    });
            })
            ->latest()
            ->paginate(10);

        return view('feed', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'video' => 'nullable|mimes:mp4,avi,mov|max:51200',
            'privacy' => 'required|in:public,friends,private'
        ]);

        $post = new Post();
        $post->user_id = Auth::id();
        $post->content = $request->content;
        $post->privacy = $request->privacy ?? 'public';

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts/images', 'public');
            $post->image_path = $imagePath;
        }

        // Handle video upload
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('posts/videos', 'public');
            $post->video_path = $videoPath;
        }

        $post->save();

        return redirect()->route('feed')->with('success', 'Post created successfully!');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete associated files
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }
        if ($post->video_path) {
            Storage::disk('public')->delete($post->video_path);
        }

        $post->delete();

        return redirect()->route('feed')->with('success', 'Post deleted successfully!');
    }
}
