<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class OfficerController extends Controller
{
    public function index()
    {
        $officers = User::where('role', User::ROLE_OFFICER)->paginate(10);
        return view('admin.officers.index', compact('officers'));
    }

    public function create()
    {
        $departments = User::getDepartments();
        
        // Load department name to category mapping for live preview in UI
        $departmentsWithCategories = [];
        if (class_exists(\App\Models\Department::class)) {
            $departmentsWithCategories = \App\Models\Department::all()->mapWithKeys(function($dept) {
                return [$dept->name => $dept->categories ?? []];
            })->toArray();
        }

        return view('admin.officers.create', compact('departments', 'departmentsWithCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'department' => ['required', 'string'],
        ]);

        $categories = [];
        if (class_exists(\App\Models\Department::class)) {
            $dept = \App\Models\Department::where('name', $request->department)->first();
            if ($dept) {
                $categories = $dept->categories ?? [];
            }
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_OFFICER,
            'department' => $request->department,
            'categories' => $categories,
        ]);

        return redirect()->route('admin.officers.index')->with('status', 'Officer created successfully!');
    }

    public function destroy(User $user)
    {
        if ($user->role !== User::ROLE_OFFICER) {
            abort(403);
        }

        $user->delete();
        return back()->with('status', 'Officer deleted successfully!');
    }
}
