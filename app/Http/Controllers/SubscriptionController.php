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

        if ($request->wantsJson()) {
            return response()->json([
                'is_subscribed' => (bool)$user->is_subscribed,
                'message' => $user->is_subscribed ? 'Subscribed to email updates!' : 'Unsubscribed from email updates.'
            ]);
        }

        return back()->with('status', $user->is_subscribed ? 'Subscribed to email updates!' : 'Unsubscribed from email updates.');
    }
}
