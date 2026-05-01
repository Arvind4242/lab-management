@extends('layouts.admin')
@section('title','Subscription Details')
@section('page-title','Subscription Details')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.subscriptions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">← Back to Subscriptions</a>
</div>

<div class="max-w-2xl space-y-5">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-semibold text-gray-900">Subscription #{{ $subscription->id }}</h3>
            <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold {{ $subscription->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                {{ $subscription->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div><dt class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">User</dt><dd class="font-medium text-gray-900">{{ $subscription->user?->name }}</dd></div>
            <div><dt class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Lab</dt><dd class="text-gray-700">{{ $subscription->lab?->name }}</dd></div>
            <div><dt class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Plan</dt><dd class="text-gray-700">{{ $subscription->plan_id ? ($subscription->plan?->name ?? '—') : ucfirst(str_replace('_',' ',$subscription->plan)) }}</dd></div>
            <div><dt class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Billing Cycle</dt><dd class="text-gray-700">{{ ucfirst($subscription->billing_cycle ?? 'monthly') }}</dd></div>
            <div><dt class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Start Date</dt><dd class="text-gray-700">{{ $subscription->start_date?->format('d M Y') }}</dd></div>
            <div><dt class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">End Date</dt><dd class="text-gray-700">{{ $subscription->end_date?->format('d M Y') ?? 'No expiry' }}</dd></div>
            @if($subscription->razorpay_payment_id)
            <div class="col-span-2"><dt class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Payment ID</dt><dd class="text-gray-700 font-mono text-xs">{{ $subscription->razorpay_payment_id }}</dd></div>
            <div class="col-span-2"><dt class="text-gray-500 text-xs uppercase tracking-wider mb-0.5">Order ID</dt><dd class="text-gray-700 font-mono text-xs">{{ $subscription->razorpay_order_id }}</dd></div>
            @endif
        </dl>
        <div class="mt-5 pt-5 border-t border-gray-100">
            <form method="POST" action="{{ route('admin.subscriptions.toggle', $subscription) }}">
                @csrf @method('PATCH')
                <button class="px-4 py-2 text-sm font-medium rounded-xl transition-colors {{ $subscription->is_active ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    {{ $subscription->is_active ? 'Deactivate Subscription' : 'Activate Subscription' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
