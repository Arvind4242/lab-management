@extends('layouts.admin')
@section('title','Reports')
@section('page-title','All Reports')

@section('content')
<div class="flex flex-wrap items-center gap-3 justify-between mb-6">
    <form method="GET" class="flex items-center gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patient…" class="px-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none w-52">
        <input type="date" name="date" value="{{ request('date') }}" class="px-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
        <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium">Filter</button>
    </form>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Patient</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Lab User</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Panel</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Test Date</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($reports as $report)
            <tr class="hover:bg-gray-50/60">
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-900">{{ $report->patient_name }}</p>
                    <p class="text-gray-500 text-xs">{{ $report->gender }} · {{ $report->age }}y</p>
                </td>
                <td class="px-5 py-3 text-gray-600">{{ $report->user?->name ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $report->panel?->name ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-500">{{ $report->test_date?->format('d M Y') }}</td>
                <td class="px-5 py-3">
                    <a href="{{ route('admin.reports.show', $report) }}" class="text-indigo-600 hover:text-indigo-700 text-xs font-medium">View →</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400">No reports found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($reports->hasPages())<div class="px-5 py-4 border-t border-gray-100">{{ $reports->withQueryString()->links() }}</div>@endif
</div>
@endsection
