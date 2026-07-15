<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::paginate(10);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $categories = json_decode($request->categories, true);
        if (!is_array($categories)) {
            $categories = [];
        }

        $request->merge(['categories_array' => $categories]);

        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'categories_array' => 'required|array|min:1',
            'categories_array.*' => 'required|string|max:255',
        ]);

        Department::create([
            'name' => $request->name,
            'categories' => $categories,
        ]);

        return redirect()->route('admin.departments.index')->with('status', 'Department created successfully!');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $categories = json_decode($request->categories, true);
        if (!is_array($categories)) {
            $categories = [];
        }

        $request->merge(['categories_array' => $categories]);

        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'categories_array' => 'required|array|min:1',
            'categories_array.*' => 'required|string|max:255',
        ]);

        $oldName = $department->name;
        $newName = $request->name;

        $department->update([
            'name' => $newName,
            'categories' => $categories,
        ]);

        // Sync name updates in User & Report records
        if ($oldName !== $newName) {
            User::where('department', $oldName)->update(['department' => $newName]);
            Report::where('department', $oldName)->update(['department' => $newName]);
        }

        // Sync categories for officers belonging to this department
        $officers = User::where('role', User::ROLE_OFFICER)->where('department', $newName)->get();
        foreach ($officers as $officer) {
            $officer->update(['categories' => $categories]);
        }

        return redirect()->route('admin.departments.index')->with('status', 'Department updated successfully!');
    }

    public function destroy(Department $department)
    {
        // Safe check: prevent deletion of department with active officers or reports assigned
        $hasOfficers = User::where('department', $department->name)->exists();
        $hasReports = Report::where('department', $department->name)->exists();

        if ($hasOfficers) {
            return back()->withErrors(['error' => 'Cannot delete department: active officers are currently assigned to it.']);
        }

        if ($hasReports) {
            return back()->withErrors(['error' => 'Cannot delete department: active report records are currently associated with it.']);
        }

        $department->delete();
        return redirect()->route('admin.departments.index')->with('status', 'Department deleted successfully!');
    }
}
