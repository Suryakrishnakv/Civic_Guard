<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function toggle(Request $request)
    {
        $user = $request->user();
        $user->is_subscribed = !$user->is_subscribed;
        $user->save();

        return back()->with('status', $user->is_subscribed ? 'Subscribed to email updates!' : 'Unsubscribed from email updates.');
    }
}
