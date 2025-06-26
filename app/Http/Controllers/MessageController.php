<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        // Logic to display messages
        return view('messages.index');
    }

    public function show($id)
    {
        // Logic to display a specific message
        return view('messages.show', compact('id'));
    }

    public function send(Request $request)
    {
        // Logic to send a message
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'content' => 'required|string|max:5000',
        ]);

        // Logic to save the message

        return redirect()->route('messages.index')->with('success', 'Message sent successfully!');
    }
}
