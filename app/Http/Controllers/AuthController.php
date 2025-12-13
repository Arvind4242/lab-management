<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lab;
use App\Models\Subscription;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
     public function login(Request $request)
    {
        // dd($request->all());
        // Validate
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt login
        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['error' => 'Invalid email or password']);
        }

          return redirect()->route('filament.admin.pages.dashboard')->with('success', 'Login successful');
    }

public function signup(Request $request)
{
    // Validate user + lab fields
    $request->validate([
        'name'        => 'required|string|max:255',
        'mobile'      => 'required|string|max:20|unique:users,mobile',
        'email'       => 'required|email|unique:users,email',
        'lab_name'    => 'required|string|max:255',
        'lab_address' => 'nullable|string|max:500',
        'lab_phone'   => 'nullable|string|max:20',
        'password'    => 'required|min:8|confirmed',
    ]);

    // Create user
    $user = User::create([
        'name'     => $request->name,
        'mobile'   => $request->mobile,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Create lab
    $lab = Lab::create([
        'user_id' => $user->id,
        'name'    => $request->lab_name,
        'address' => $request->lab_address,
        'phone'   => $request->lab_phone,
    ]);

    // Create subscription for lab
   Subscription::create([
    'lab_id'     => $lab->id,
    'user_id'    => $user->id,
    'plan'       => 'free_trial',    // STRING VALUE
    'start_date' => now(),
    'end_date'   => now()->addDays(30),
    'is_active'  => true,
]);

    // Auto login
    Auth::login($user);

    return redirect()->route('filament.admin.pages.dashboard')
                     ->with('success', 'Account created successfully!');
}


}
