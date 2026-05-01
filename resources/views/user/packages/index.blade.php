@extends('layouts.user')
@section('title', 'Test Packages')
@section('page-title', 'Test Packages')

@section('content')

{{-- My Packages --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="font-semibold text-gray-900">My Custom Packages</h2>
        <p class="text-xs text-gray-500 mt-0.5">Packages you've built from individual tests</p>
    </div>
    <a href="{{ route('user.packages.create') }}" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Package
    </a>
</div>

@if($myPackages->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center mb-8">
    <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-7 h-7 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
    </div>
    <p class="text-gray-500 font-medium mb-1">No custom packages yet</p>
    <p class="text-gray-400 text-sm mb-4">Create reusable test collections for faster report entry</p>
    <a href="{{ route('user.packages.create') }}" class="inline-flex bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors">
        Create First Package
    </a>
</div>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 mb-8">
    @foreach($myPackages as $pkg)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col">
        <div class="flex items-start justify-between mb-2">
            <div class="min-w-0">
                <h3 class="font-semibold text-gray-900 truncate">{{ $pkg->name }}</h3>
                @if($pkg->description)
                <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $pkg->description }}</p>
                @endif
            </div>
            <span class="ml-2 flex-shrink-0 inline-flex px-2 py-0.5 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full">Mine</span>
        </div>

        <div class="flex items-center gap-3 mt-2 mb-4 text-xs text-gray-500">
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                {{ $pkg->tests_count }} test{{ $pkg->tests_count != 1 ? 's' : '' }}
            </span>
            @if($pkg->fee)
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                ₹{{ number_format($pkg->fee, 0) }}
            </span>
            @endif
        </div>

        <div class="mt-auto flex items-center gap-2">
            <a href="{{ route('user.reports.create') }}?package={{ $pkg->id }}"
               class="flex-1 text-center py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition-colors">
                Use in Report
            </a>
            <a href="{{ route('user.packages.edit', $pkg) }}" class="p-2 rounded-xl hover:bg-gray-100 text-gray-500 hover:text-indigo-600 transition-colors" title="Edit">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </a>
            <form method="POST" action="{{ route('user.packages.destroy', $pkg) }}" onsubmit="return confirm('Delete this package?')">
                @csrf @method('DELETE')
                <button class="p-2 rounded-xl hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Global (Admin) Packages --}}
@if($globalPackages->isNotEmpty())
<div class="mb-5">
    <h2 class="font-semibold text-gray-900">Standard Packages</h2>
    <p class="text-xs text-gray-500 mt-0.5">Packages configured by your administrator</p>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
    @foreach($globalPackages as $pkg)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col">
        <div class="flex items-start justify-between mb-2">
            <div class="min-w-0">
                <h3 class="font-semibold text-gray-900 truncate">{{ $pkg->name }}</h3>
                @if($pkg->gender)
                <p class="text-xs text-gray-400 mt-0.5">{{ ucfirst($pkg->gender) }} only</p>
                @endif
            </div>
            <span class="ml-2 flex-shrink-0 inline-flex px-2 py-0.5 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full">Standard</span>
        </div>

        <div class="flex items-center gap-3 mt-2 mb-4 text-xs text-gray-500">
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                {{ $pkg->tests_count }} test{{ $pkg->tests_count != 1 ? 's' : '' }}
            </span>
            @if($pkg->fee)
            <span>₹{{ number_format($pkg->fee, 0) }}</span>
            @endif
        </div>

        <div class="mt-auto">
            <a href="{{ route('user.reports.create') }}?package={{ $pkg->id }}"
               class="block text-center py-2 bg-gray-100 hover:bg-indigo-600 hover:text-white text-gray-700 text-xs font-semibold rounded-xl transition-colors">
                Use in Report
            </a>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
