@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">

    @php
    $cards = [
        ['label'=>'Total Users', 'value'=>$stats['total_users'], 'color'=>'indigo', 'icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
        ['label'=>'Total Labs', 'value'=>$stats['total_labs'], 'color'=>'purple', 'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
        ['label'=>'Reports Today', 'value'=>$stats['reports_today'], 'color'=>'blue', 'icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['label'=>'Total Reports', 'value'=>$stats['total_reports'], 'color'=>'emerald', 'icon'=>'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['label'=>'Active Subscriptions', 'value'=>$stats['active_subs'], 'color'=>'teal', 'icon'=>'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
        ['label'=>'Expired Subscriptions', 'value'=>$stats['expired_subs'], 'color'=>'red', 'icon'=>'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];
    @endphp

    @foreach($cards as $card)
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

{{-- Tables Row --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- Recent Users --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Recent Users</h3>
            <a href="{{ route('admin.users.index') }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">View all →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentUsers as $u)
            <div class="flex items-center gap-3 px-5 py-3">
                <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm flex-shrink-0">{{ strtoupper(substr($u->name,0,1)) }}</div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $u->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $u->lab?->name ?? 'No lab' }} · {{ $u->email }}</p>
                </div>
                <span class="ml-auto text-xs text-gray-400">{{ $u->created_at?->diffForHumans() ?? '—' }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 px-5 py-8 text-center">No users yet</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Reports --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Recent Reports</h3>
            <a href="{{ route('admin.reports.index') }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">View all →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentReports as $r)
            <div class="flex items-center gap-3 px-5 py-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $r->patient_name }}</p>
                    <p class="text-xs text-gray-500">{{ $r->user?->name ?? '—' }} · {{ $r->test_date?->format('d M Y') }}</p>
                </div>
                <a href="{{ route('admin.reports.show', $r) }}" class="ml-auto text-xs text-indigo-600 hover:underline">View</a>
            </div>
            @empty
            <p class="text-sm text-gray-400 px-5 py-8 text-center">No reports yet</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Subscriptions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden xl:col-span-2">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Recent Subscriptions</h3>
            <a href="{{ route('admin.subscriptions.index') }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-5 py-3 font-medium">User / Lab</th>
                        <th class="text-left px-5 py-3 font-medium">Plan</th>
                        <th class="text-left px-5 py-3 font-medium">Period</th>
                        <th class="text-left px-5 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentSubs as $s)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-900">{{ $s->user?->name ?? '—' }}</p>
                            <p class="text-gray-500 text-xs">{{ $s->lab?->name ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $s->plan_id ? ($s->plan?->name ?? '—') : ucfirst(str_replace('_',' ',$s->plan)) }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $s->start_date?->format('d M') }} – {{ $s->end_date?->format('d M Y') ?? '∞' }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $s->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                                {{ $s->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">No subscriptions yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
