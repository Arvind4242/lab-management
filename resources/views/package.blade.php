@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 py-10 px-4">

    <div class="max-w-5xl mx-auto">

        {{-- User Details Section --}}
        <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl shadow-xl p-6 mb-10">
            <div class="flex items-center gap-3 mb-5">
                <div class="text-3xl bg-green-500 text-white w-12 h-12 flex items-center justify-center rounded-xl shadow-lg">👤</div>
                <h2 class="text-xl font-bold text-white">User Profile</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-white">
                <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                <p><strong>Mobile:</strong> {{ auth()->user()->phone ?? 'N/A' }}</p>
                <p><strong>City:</strong> {{ auth()->user()->city ?? 'N/A' }}</p>
                <p><strong>State:</strong> {{ auth()->user()->state ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ auth()->user()->address ?? 'N/A' }}</p>
                <p><strong>Joined On:</strong> {{ auth()->user()->created_at->format('d M, Y') }}</p>
            </div>
        </div>

        {{-- Packages Section --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="text-3xl bg-yellow-500 text-white w-12 h-12 flex items-center justify-center rounded-xl shadow-lg">🧪</div>
            <h2 class="text-2xl font-bold text-white">Lab Packages</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Package 1 --}}
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-[1.02] transition p-5">
                <h3 class="text-lg font-bold text-gray-900">Basic Health Package</h3>
                <p class="text-sm text-gray-600 mt-2">Includes: CBC, Sugar, BP, Thyroid</p>
                <div class="text-xl font-semibold mt-3">₹499</div>
                <a href="{{ route('payment.page') }}"
                   class="mt-4 inline-block w-full text-center px-4 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-medium hover:opacity-90">
                    Buy Now
                </a>
            </div>

            {{-- Package 2 --}}
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-[1.02] transition p-5">
                <h3 class="text-lg font-bold text-gray-900">Advanced Package</h3>
                <p class="text-sm text-gray-600 mt-2">Includes: Liver, Kidney, Lipid, Vitamin</p>
                <div class="text-xl font-semibold mt-3">₹999</div>
                <a href="{{ route('payment.page') }}"
                   class="mt-4 inline-block w-full text-center px-4 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-medium hover:opacity-90">
                    Buy Now
                </a>
            </div>

            {{-- Package 3 --}}
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-[1.02] transition p-5">
                <h3 class="text-lg font-bold text-gray-900">Full Body Checkup</h3>
                <p class="text-sm text-gray-600 mt-2">Includes: All major tests + ECG</p>
                <div class="text-xl font-semibold mt-3">₹1,799</div>
                <a href="{{ route('payment.page') }}"
                   class="mt-4 inline-block w-full text-center px-4 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-medium hover:opacity-90">
                    Buy Now
                </a>
            </div>

        </div>

    </div>

</div>

@endsection
