<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $messages = ContactMessage::latest()->paginate(20);
        return view('admin.messages.index', compact('messages'));
    }

    public function show(int $id): View
    {
        $message = ContactMessage::findOrFail($id);
        if (!$message->is_read) {
            $message->is_read = true;
            $message->save();
        }

        return view('admin.messages.show', compact('message'));
    }

    public function toggleRead(int $id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->is_read = !$message->is_read;
        $message->save();

        return back()->with('success', 'Message marked as ' . ($message->is_read ? 'read' : 'unread') . '.');
    }

    public function destroy(int $id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Message deleted.');
    }
}
