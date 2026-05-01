<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\User;
use Illuminate\Http\Request;

class LabController extends Controller
{
    public function index(Request $request)
    {
        $labs = Lab::with('user')
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15);

        return view('admin.labs.index', compact('labs'));
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.labs.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone'   => 'nullable|string|max:20',
            'user_id' => 'nullable|exists:users,id',
        ]);

        Lab::create($request->only('name', 'address', 'phone', 'user_id'));

        return redirect()->route('admin.labs.index')->with('success', 'Lab created successfully.');
    }

    public function edit(Lab $lab)
    {
        $users = User::where('role', 'user')->get();
        return view('admin.labs.edit', compact('lab', 'users'));
    }

    public function update(Request $request, Lab $lab)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone'   => 'nullable|string|max:20',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $lab->update($request->only('name', 'address', 'phone', 'user_id'));

        return redirect()->route('admin.labs.index')->with('success', 'Lab updated successfully.');
    }

    public function destroy(Lab $lab)
    {
        $lab->delete();
        return redirect()->route('admin.labs.index')->with('success', 'Lab deleted.');
    }
}
