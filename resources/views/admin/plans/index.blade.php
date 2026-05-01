@extends('layouts.admin')
@section('title','Plans')
@section('page-title','Subscription Plans')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $plans->count() }} plan(s) configured</p>
    <a href="{{ route('admin.plans.create') }}" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Plan
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
    @forelse($plans as $plan)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col">
        <div class="flex items-start justify-between mb-3">
            <div>
                <h3 class="font-bold text-gray-900">{{ $plan->name }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">{{ $plan->slug }}</p>
            </div>
            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $plan->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                {{ $plan->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <p class="text-gray-500 text-xs mb-4">{{ $plan->description }}</p>

        <div class="mb-4">
            <span class="text-2xl font-extrabold text-gray-900">₹{{ number_format($plan->price_monthly, 0) }}</span>
            <span class="text-gray-500 text-xs">/mo</span>
            @if($plan->price_yearly > 0)
            <p class="text-xs text-indigo-600 mt-0.5">₹{{ number_format($plan->price_yearly, 0) }}/year</p>
            @endif
        </div>

        @if($plan->features)
        <ul class="space-y-1.5 mb-5 flex-1">
            @foreach($plan->features as $feature)
            <li class="flex items-center gap-2 text-xs text-gray-600">
                <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                {{ $feature }}
            </li>
            @endforeach
        </ul>
        @endif

        <div class="mt-auto pt-3 border-t border-gray-100 flex items-center gap-2">
            <span class="text-xs text-gray-400">{{ $plan->subscriptions_count }} subscribers</span>
            <div class="ml-auto flex items-center gap-2">
                <a href="{{ route('admin.plans.edit', $plan) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?')">
                    @csrf @method('DELETE')
                    <button class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-4 text-center py-16 text-gray-400">No plans yet. <a href="{{ route('admin.plans.create') }}" class="text-indigo-600 underline">Add one.</a></div>
    @endforelse
</div>
@endsection
