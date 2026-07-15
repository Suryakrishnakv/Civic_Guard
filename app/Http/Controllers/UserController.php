<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'citizen')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'citizen') {
            abort(403);
        }

        $user->delete();
        return back()->with('status', 'Citizen deleted successfully!');
    }
}
