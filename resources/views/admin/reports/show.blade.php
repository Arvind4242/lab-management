@extends('layouts.admin')
@section('title','Report')
@section('page-title','Report — {{ $report->patient_name }}')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.reports.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">← Back to Reports</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Test Results</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Test / Parameter</th>
                            <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Value</th>
                            <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit</th>
                            <th class="text-left px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ref. Range</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($report->results as $r)
                        <tr class="hover:bg-gray-50/60">
                            <td class="px-4 py-2.5 text-gray-900">{{ $r->parameter_name ?? $r->test_name }}</td>
                            <td class="px-4 py-2.5 font-medium text-gray-900">{{ $r->value ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-gray-500">{{ $r->unit ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-gray-500 text-xs">{{ $r->reference_range ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">No results</td></tr>
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
                <div><dt class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Name</dt><dd class="font-medium text-gray-900">{{ $report->patient_name }}</dd></div>
                <div><dt class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Age / Gender</dt><dd class="text-gray-700">{{ $report->age }}y · {{ ucfirst($report->gender) }}</dd></div>
                <div><dt class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Referred by</dt><dd class="text-gray-700">{{ $report->referred_by ?? '—' }}</dd></div>
                <div><dt class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Test Date</dt><dd class="text-gray-700">{{ $report->test_date?->format('d M Y') }}</dd></div>
                <div><dt class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Panel</dt><dd class="text-gray-700">{{ $report->panel?->name ?? '—' }}</dd></div>
            </dl>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 mb-4">Lab User</h3>
            <p class="text-sm font-medium text-gray-900">{{ $report->user?->name ?? '—' }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $report->user?->email }}</p>
        </div>
    </div>
</div>
@endsection
