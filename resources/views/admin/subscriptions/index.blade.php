@extends('layouts.admin')
@section('title','Subscriptions')
@section('page-title','Subscriptions')

@section('content')
<div class="flex flex-wrap items-center gap-3 justify-between mb-6">
    <form method="GET" class="flex items-center gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user…" class="px-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none w-52">
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            <option value="">All status</option>
            <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
            <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
        </select>
        <button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium">Filter</button>
    </form>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">User / Lab</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Period</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($subscriptions as $sub)
            <tr class="hover:bg-gray-50/60">
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-900">{{ $sub->user?->name ?? '—' }}</p>
                    <p class="text-gray-500 text-xs">{{ $sub->lab?->name ?? '—' }}</p>
                </td>
                <td class="px-5 py-3 text-gray-600">{{ $sub->plan_id ? ($sub->plan?->name ?? '—') : ucfirst(str_replace('_',' ',$sub->plan)) }}</td>
                <td class="px-5 py-3 text-gray-500 text-xs">{{ $sub->start_date?->format('d M Y') }} – {{ $sub->end_date?->format('d M Y') ?? '∞' }}</td>
                <td class="px-5 py-3">
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $sub->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                        {{ $sub->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-2 justify-end">
                        <a href="{{ route('admin.subscriptions.show', $sub) }}" class="text-indigo-600 hover:text-indigo-700 text-xs font-medium">View</a>
                        <form method="POST" action="{{ route('admin.subscriptions.toggle', $sub) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs font-medium {{ $sub->is_active ? 'text-red-500 hover:text-red-700' : 'text-emerald-600 hover:text-emerald-700' }}">
                                {{ $sub->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400">No subscriptions found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($subscriptions->hasPages())<div class="px-5 py-4 border-t border-gray-100">{{ $subscriptions->withQueryString()->links() }}</div>@endif
</div>
@endsection
