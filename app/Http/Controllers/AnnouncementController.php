<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Notification::where('type', 'system')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Create notification for all users
        $users = User::all();
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'system',
                'title' => $request->title,
                'message' => $request->message,
                'is_read' => false,
            ]);
        }

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement sent successfully to all users.');
    }

    public function destroy(Notification $announcement)
    {
        if ($announcement->type !== 'system') {
            return redirect()->back()->with('error', 'Invalid announcement.');
        }

        // Delete the announcement for all users
        Notification::where('type', 'system')
            ->where('title', $announcement->title)
            ->where('message', $announcement->message)
            ->where('created_at', $announcement->created_at)
            ->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
} 