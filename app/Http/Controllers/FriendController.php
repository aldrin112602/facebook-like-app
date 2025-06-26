<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function index()
    {
        // Logic to display friends list
        return view('friends.index');
    }

    public function add(Request $request)
    {
        // Logic to add a friend
        return redirect()->route('friends.index')->with('success', 'Friend added successfully!');
    }

    public function remove($id)
    {
        // Logic to remove a friend
        return redirect()->route('friends.index')->with('success', 'Friend removed successfully!');
    }
}
