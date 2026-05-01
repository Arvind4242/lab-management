<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('lab')
            ->where('role', '!=', 'admin')
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%")->orWhere('email', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $labs = Lab::all();
        return view('admin.users.create', compact('labs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'mobile'   => 'required|string|max:20|unique:users',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:admin,user',
            'lab_id'   => 'nullable|exists:labs,id',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'mobile'   => $request->mobile,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'lab_id'   => $request->lab_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $labs = Lab::all();
        return view('admin.users.edit', compact('user', 'labs'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'required|string|max:20|unique:users,mobile,' . $user->id,
            'role'   => 'required|in:admin,user',
            'lab_id' => 'nullable|exists:labs,id',
        ]);

        $data = $request->only('name', 'email', 'mobile', 'role', 'lab_id');

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}
