@extends('layouts.user')
@section('title', 'Subscription')
@section('page-title', 'My Subscription')

@section('content')
<div class="max-w-2xl space-y-6">

    @if($subscription && $subscription->is_active)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-semibold text-gray-900">Current Plan</h3>
            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-sm font-semibold rounded-full">Active</span>
        </div>

        <div class="flex items-start gap-5 p-4 bg-indigo-50 rounded-2xl mb-5">
            <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <div>
                <p class="text-xl font-bold text-gray-900">
                    {{ $subscription->plan_id ? ($subscription->plan?->name ?? 'Plan') : ucfirst(str_replace('_',' ',$subscription->plan)) }}
                </p>
                <p class="text-sm text-gray-600 mt-0.5">
                    @if($subscription->billing_cycle) {{ ucfirst($subscription->billing_cycle) }} billing @endif
                </p>
            </div>
        </div>

        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div class="bg-gray-50 rounded-xl p-3">
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Start Date</dt>
                <dd class="font-semibold text-gray-900">{{ $subscription->start_date?->format('d M Y') }}</dd>
            </div>
            <div class="bg-gray-50 rounded-xl p-3">
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Expires</dt>
                <dd class="font-semibold text-gray-900">{{ $subscription->end_date?->format('d M Y') ?? 'No expiry' }}</dd>
            </div>
            @if($subscription->plan)
            <div class="bg-gray-50 rounded-xl p-3">
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Max Reports</dt>
                <dd class="font-semibold text-gray-900">{{ $subscription->plan->max_reports == -1 ? 'Unlimited' : $subscription->plan->max_reports }}</dd>
            </div>
            <div class="bg-gray-50 rounded-xl p-3">
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Max Users</dt>
                <dd class="font-semibold text-gray-900">{{ $subscription->plan->max_users == -1 ? 'Unlimited' : $subscription->plan->max_users }}</dd>
            </div>
            @endif
            @if($subscription->razorpay_payment_id)
            <div class="col-span-2 bg-gray-50 rounded-xl p-3">
                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Payment ID</dt>
                <dd class="font-mono text-xs text-gray-600">{{ $subscription->razorpay_payment_id }}</dd>
            </div>
            @endif
        </dl>

        @if($subscription->plan && $subscription->plan->features)
        <div class="mt-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Included Features</p>
            <div class="grid grid-cols-2 gap-2">
                @foreach($subscription->plan->features as $feature)
                <div class="flex items-center gap-2 text-sm text-gray-700">
                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    {{ $feature }}
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mt-6 pt-5 border-t border-gray-100">
            <a href="{{ route('user.subscription.plans') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">
                Upgrade / Change Plan
            </a>
        </div>
    </div>

    @else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
        <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">No Active Subscription</h3>
        <p class="text-sm text-gray-500 mb-6">Subscribe to a plan to unlock all features and continue creating reports.</p>
        <a href="{{ route('user.subscription.plans') }}" class="inline-flex bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-sm font-semibold transition-colors">
            View Plans & Subscribe
        </a>
    </div>
    @endif

</div>
@endsection
