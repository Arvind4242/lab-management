@extends('layouts.user')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@php $user = auth()->user(); @endphp

{{-- Subscription banner --}}
@if(!$subscription || !$subscription->is_active)
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex items-center gap-4">
    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    </div>
    <div class="flex-1">
        <p class="text-sm font-semibold text-amber-800">No active subscription</p>
        <p class="text-xs text-amber-600 mt-0.5">Your free trial may have ended. Upgrade to continue creating reports.</p>
    </div>
    <a href="{{ route('user.subscription.plans') }}" class="flex-shrink-0 bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors">
        View Plans
    </a>
</div>
@elseif($subscription->end_date && $subscription->end_date->diffInDays(now()) > -7 && $subscription->end_date->isFuture())
<div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6 flex items-center gap-4">
    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p class="text-sm text-blue-800">Your subscription expires on <strong>{{ $subscription->end_date?->format('d M Y') }}</strong>. <a href="{{ route('user.subscription.plans') }}" class="underline font-medium">Renew now →</a></p>
</div>
@endif

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    @php
    $statCards = [
        ['label' => 'Reports Today',  'value' => $stats['reports_today'],  'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'indigo'],
        ['label' => 'This Month',     'value' => $stats['reports_month'],  'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'purple'],
        ['label' => 'Total Reports',  'value' => $stats['total_reports'],  'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'blue'],
    ];
    @endphp

    @foreach($statCards as $card)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-{{ $card['color'] }}-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-{{ $card['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
            <p class="text-sm text-gray-500 mt-0.5">{{ $card['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Recent reports --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Recent Reports</h3>
            <a href="{{ route('user.reports.index') }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">View all →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentReports as $report)
            <div class="flex items-center gap-4 px-5 py-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $report->patient_name }}</p>
                    <p class="text-xs text-gray-500">{{ $report->panel?->name ?? 'No panel' }} · {{ $report->test_date?->format('d M Y') }}</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('user.reports.show', $report) }}" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-indigo-600 transition-colors" title="View">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                    <a href="{{ route('user.reports.print', $report) }}" target="_blank" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors" title="Print">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="px-5 py-12 text-center">
                <p class="text-gray-400 text-sm mb-3">No reports yet</p>
                <a href="{{ route('user.reports.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors">Create your first report</a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Subscription card --}}
    <div class="space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 mb-4">Subscription</h3>
            @if($subscription && $subscription->is_active)
                <div class="text-center py-2">
                    <span class="inline-flex px-3 py-1.5 bg-emerald-50 text-emerald-700 text-sm font-semibold rounded-full">Active</span>
                    <p class="text-lg font-bold text-gray-900 mt-3">
                        {{ $subscription->plan_id ? ($subscription->plan?->name ?? 'Plan') : ucfirst(str_replace('_',' ',$subscription->plan)) }}
                    </p>
                    @if($subscription->end_date)
                    <p class="text-sm text-gray-500 mt-1">Expires {{ $subscription->end_date?->format('d M Y') ?? 'No expiry' }}</p>
                    @else
                    <p class="text-sm text-gray-500 mt-1">No expiry date</p>
                    @endif
                </div>
                <a href="{{ route('user.subscription.plans') }}" class="mt-4 block text-center text-sm text-indigo-600 hover:text-indigo-700 font-medium">Upgrade plan →</a>
            @else
                <div class="text-center py-2">
                    <span class="inline-flex px-3 py-1.5 bg-red-50 text-red-600 text-sm font-semibold rounded-full">Inactive</span>
                    <p class="text-sm text-gray-500 mt-3">Subscribe to unlock all features</p>
                </div>
                <a href="{{ route('user.subscription.plans') }}" class="mt-4 block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl text-sm font-medium transition-colors">Choose a Plan</a>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('user.reports.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-indigo-50 text-gray-700 hover:text-indigo-700 transition-colors text-sm font-medium">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Report
                </a>
                <a href="{{ route('user.reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 text-gray-700 hover:text-gray-900 transition-colors text-sm font-medium">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    All Reports
                </a>
                <a href="{{ route('user.profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 text-gray-700 hover:text-gray-900 transition-colors text-sm font-medium">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
