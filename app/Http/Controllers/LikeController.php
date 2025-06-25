<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $user = Auth::user();
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $action = 'unliked';
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            $action = 'liked';
        }

        return response()->json([
            'action' => $action,
            'likes_count' => $post->likesCount()
        ]);
    }
}
