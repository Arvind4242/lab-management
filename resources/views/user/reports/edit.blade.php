@extends('layouts.user')
@section('title', 'Edit Report')
@section('page-title', 'Edit Report')

@section('content')
<form method="POST" action="{{ route('user.reports.update', $report) }}">
@csrf @method('PUT')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Patient Info --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 mb-4">Patient Details</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Patient Name *</label>
                    <input type="text" name="patient_name" value="{{ old('patient_name', $report->patient_name) }}" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Age</label>
                        <input type="text" name="age" value="{{ old('age', $report->age) }}"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Gender</label>
                        <select name="gender" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="male"   {{ old('gender',$report->gender)=='male'   ?'selected':'' }}>Male</option>
                            <option value="female" {{ old('gender',$report->gender)=='female' ?'selected':'' }}>Female</option>
                            <option value="other"  {{ old('gender',$report->gender)=='other'  ?'selected':'' }}>Other</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Referred By</label>
                    <input type="text" name="referred_by" value="{{ old('referred_by', $report->referred_by) }}"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Client Name</label>
                    <input type="text" name="client_name" value="{{ old('client_name', $report->client_name) }}"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Test Date</label>
                    <input type="date" name="test_date" value="{{ old('test_date', $report->test_date?->format('Y-m-d')) }}" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>
        </div>
    </div>

    {{-- Results --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Test Results</h3>
                <span class="text-xs text-gray-500">{{ $report->report_results->count() }} tests</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="text-left px-4 py-2.5 font-semibold">Test / Parameter</th>
                            <th class="text-left px-4 py-2.5 font-semibold w-32">Value</th>
                            <th class="text-left px-4 py-2.5 font-semibold w-20">Unit</th>
                            <th class="text-left px-4 py-2.5 font-semibold w-32">Ref. Range</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($report->report_results()->orderBy('display_order')->orderBy('id')->get() as $idx => $result)
                        <tr class="hover:bg-gray-50/60">
                            <td class="px-4 py-2.5">
                                <input type="hidden" name="tests[{{ $idx }}][id]" value="{{ $result->id }}">
                                <span class="text-sm text-gray-900">{{ $result->parameter_name ?? $result->test_name }}</span>
                            </td>
                            <td class="px-4 py-2.5">
                                <input type="text" name="tests[{{ $idx }}][value]" value="{{ $result->value }}"
                                    class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 outline-none">
                            </td>
                            <td class="px-4 py-2.5 text-gray-500 text-xs">{{ $result->unit ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-gray-500 text-xs">{{ $result->reference_range ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('user.reports.show', $report) }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium">← Back</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
</form>
@endsection
