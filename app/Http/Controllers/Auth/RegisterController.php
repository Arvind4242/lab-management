<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'mobile'       => 'required|string|max:20|unique:users,mobile',
            'email'        => 'required|email|unique:users,email',
            'lab_name'     => 'required|string|max:255',
            'lab_address'  => 'nullable|string|max:500',
            'lab_phone'    => 'nullable|string|max:20',
            'password'     => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'mobile'   => $request->mobile,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        $lab = Lab::create([
            'user_id' => $user->id,
            'name'    => $request->lab_name,
            'address' => $request->lab_address,
            'phone'   => $request->lab_phone,
        ]);

        $user->update(['lab_id' => $lab->id]);

        Subscription::create([
            'lab_id'     => $lab->id,
            'user_id'    => $user->id,
            'plan'       => 'free_trial',
            'start_date' => now(),
            'end_date'   => now()->addDays(30),
            'is_active'  => true,
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Welcome! Your free trial has started.');
    }
}
