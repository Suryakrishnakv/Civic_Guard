<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'via_in_app' => 'nullable|boolean',
            'via_email' => 'nullable|boolean',
        ]);

        $announcement = \App\Models\Announcement::create([
            'title' => $request->title,
            'message' => $request->message,
            'user_id' => auth()->id(),
            'audience' => $request->input('audience', 'citizens'),
        ]);

        $inApp = $request->via_in_app;
        $email = $request->via_email;
        $audience = $request->input('audience', 'citizens');

        $query = \App\Models\User::query()->where('id', '!=', auth()->id());

        if ($audience === 'citizens') {
            $query->where('role', 'citizen');
        } elseif ($audience === 'officers') {
            // Include Admins so they can see the message too
            $query->whereIn('role', ['officer', 'admin']);
        } 
        // 'all' implies no filtering by role (sends to everyone)

        if ($inApp) {
            // Get users for In-App notification
            $users = (clone $query)->get();
            \Illuminate\Support\Facades\Notification::send($users, (new \App\Notifications\NewAnnouncement($announcement))->setChannels(['database']));
        }

        if ($email) {
            // Get subscribed users for Email notification
             // If sending to officers, we might assume they are "subscribed" by default or check a preference. 
             // For now, let's respect the 'is_subscribed' flag for everyone, or maybe enforce it for citizens only.
             // Let's keep it simple: Only send email if 'is_subscribed' is true.
            $subscribedUsers = (clone $query)->where('is_subscribed', true)->get();
            
            if ($subscribedUsers->count() > 0) {
                 \Illuminate\Support\Facades\Notification::send($subscribedUsers, (new \App\Notifications\NewAnnouncement($announcement))->setChannels(['mail']));
            }
        }

        return back()->with('status', 'Announcement published successfully!');
    }
    public function index()
    {
        $sentBroadcasts = \App\Models\Announcement::where('user_id', auth()->id())->latest()->get();
        return view('admin.broadcasts', compact('sentBroadcasts'));
    }
}
