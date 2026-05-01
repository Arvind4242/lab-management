<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('user.profile.index', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'         => 'required|string|max:255',
            'mobile'       => 'required|string|max:20|unique:users,mobile,' . $user->id,
            'address'      => 'nullable|string|max:500',
            'website'      => 'nullable|url|max:255',
            'qualification'=> 'nullable|string|max:255',
            'note'         => 'nullable|string',
            'logo'         => 'nullable|image|max:2048',
            'digital_signature' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'mobile', 'address', 'website', 'qualification', 'note', 'reference_lab');

        if ($request->hasFile('logo')) {
            if ($user->logo) Storage::disk('public')->delete($user->logo);
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        if ($request->hasFile('digital_signature')) {
            if ($user->digital_signature) Storage::disk('public')->delete($user->digital_signature);
            $data['digital_signature'] = $request->file('digital_signature')->store('signatures', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully.');
    }
}
