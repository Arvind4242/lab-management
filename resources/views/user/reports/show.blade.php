@extends('layouts.user')
@section('title', 'Report — ' . $report->patient_name)
@section('page-title', $report->patient_name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('user.reports.index') }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 font-medium">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Reports
    </a>
    <div class="flex items-center gap-2">
        <a href="{{ route('user.reports.edit', $report) }}" class="flex items-center gap-2 px-4 py-2 border border-gray-300 hover:border-gray-400 text-gray-700 rounded-xl text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </a>
        <a href="{{ route('user.reports.print', $report) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 border border-gray-300 hover:border-gray-400 text-gray-700 rounded-xl text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print
        </a>
        <a href="{{ route('user.reports.download', $report) }}" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            PDF
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Test Results</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold">Test / Parameter</th>
                            <th class="text-left px-5 py-3 font-semibold">Value</th>
                            <th class="text-left px-5 py-3 font-semibold">Unit</th>
                            <th class="text-left px-5 py-3 font-semibold">Ref. Range</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($report->results as $result)
                        <tr class="hover:bg-gray-50/60">
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $result->parameter_name ?? $result->test_name }}</td>
                            <td class="px-5 py-3 font-semibold text-gray-900">{{ $result->value ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-500 text-xs">{{ $result->unit ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-500 text-xs">{{ $result->reference_range ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400">No results</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 mb-4">Patient Info</h3>
            <dl class="space-y-3 text-sm">
                <div><dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Name</dt><dd class="font-medium text-gray-900">{{ $report->patient_name }}</dd></div>
                <div><dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Age / Gender</dt><dd class="text-gray-700">{{ $report->age }} · {{ ucfirst($report->gender) }}</dd></div>
                <div><dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Referred by</dt><dd class="text-gray-700">{{ $report->referred_by ?? '—' }}</dd></div>
                <div><dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Client</dt><dd class="text-gray-700">{{ $report->client_name ?? '—' }}</dd></div>
                <div><dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Test Date</dt><dd class="text-gray-700">{{ $report->test_date?->format('d M Y') }}</dd></div>
                <div><dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Panel</dt><dd class="text-gray-700">{{ $report->panel?->name ?? '—' }}</dd></div>
            </dl>
        </div>
    </div>
</div>
@endsection
