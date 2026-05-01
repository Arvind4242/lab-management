@extends('layouts.user')
@section('title', 'Choose Plan')
@section('page-title', 'Choose a Plan')

@section('content')

<p class="text-sm text-gray-500 mb-8">All plans include PDF reports, test panels & packages. Cancel anytime.</p>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
    @foreach($plans as $plan)
    @php $isCurrent = $currentSub && $currentSub->plan_id == $plan->id; @endphp

    {{-- Single x-data wrapper per card controls BOTH the toggle and the subscribe button --}}
    <div x-data="{ yearly: false }"
         class="bg-white rounded-2xl shadow-sm border {{ $plan->slug === 'professional' ? 'border-indigo-400 ring-2 ring-indigo-200' : 'border-gray-100' }} p-5 flex flex-col relative">

        @if($plan->slug === 'professional')
        <div class="absolute -top-3 left-1/2 -translate-x-1/2">
            <span class="bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full">Most Popular</span>
        </div>
        @endif

        {{-- Plan name & description --}}
        <div class="mb-4">
            <h3 class="text-lg font-bold text-gray-900">{{ $plan->name }}</h3>
            <p class="text-xs text-gray-500 mt-0.5">{{ $plan->description }}</p>
        </div>

        {{-- Pricing --}}
        <div class="mb-5">
            @if($plan->price_monthly > 0)
            {{-- Monthly / Yearly toggle --}}
            <div class="flex items-center gap-2 mb-3">
                <span class="text-xs" :class="!yearly ? 'font-semibold text-gray-700' : 'text-gray-400'">Monthly</span>
                <button type="button" @click="yearly = !yearly"
                    class="relative w-10 h-5 rounded-full transition-colors flex-shrink-0"
                    :class="yearly ? 'bg-indigo-600' : 'bg-gray-200'">
                    <span class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform"
                          :class="yearly ? 'translate-x-5' : 'translate-x-0'"></span>
                </button>
                <span class="text-xs" :class="yearly ? 'font-semibold text-gray-700' : 'text-gray-400'">
                    Yearly <span class="text-emerald-600 font-semibold">–17%</span>
                </span>
            </div>
            <div x-show="!yearly">
                <span class="text-3xl font-extrabold text-gray-900">₹{{ number_format($plan->price_monthly, 0) }}</span>
                <span class="text-gray-400 text-sm">/month</span>
            </div>
            <div x-show="yearly" x-cloak>
                <span class="text-3xl font-extrabold text-gray-900">₹{{ number_format($plan->price_yearly, 0) }}</span>
                <span class="text-gray-400 text-sm">/year</span>
            </div>
            @else
            <span class="text-3xl font-extrabold text-gray-900">Free</span>
            @endif
        </div>

        {{-- Features --}}
        @if($plan->features)
        <ul class="space-y-2 mb-6 flex-1">
            @foreach($plan->features as $feature)
            <li class="flex items-center gap-2 text-sm text-gray-600">
                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $feature }}
            </li>
            @endforeach
        </ul>
        @endif

        {{-- CTA button — yearly is in scope because we're inside the same x-data div --}}
        <div class="mt-auto">
            @if($isCurrent)
            <div class="py-2.5 text-center bg-emerald-50 text-emerald-700 font-semibold text-sm rounded-xl">
                ✓ Current Plan
            </div>
            @elseif($plan->price_monthly == 0)
            <div class="py-2.5 text-center bg-gray-50 text-gray-500 font-medium text-sm rounded-xl">
                Free Trial
            </div>
            @else
            <button type="button"
                @click="openCheckout({{ $plan->id }}, '{{ addslashes($plan->name) }}', {{ (int)($plan->price_monthly * 100) }}, {{ (int)($plan->price_yearly * 100) }}, yearly)"
                class="w-full py-2.5 font-semibold text-sm rounded-xl transition-colors {{ $plan->slug === 'professional' ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-gray-900 hover:bg-black text-white' }}">
                Subscribe
            </button>
            @endif
        </div>

    </div>
    @endforeach
</div>

{{-- Payment confirmation modal --}}
<div id="paymentModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 text-base">Complete Payment</h3>
                <p id="modalPlanName" class="text-gray-500 text-sm"></p>
            </div>
        </div>
        <div id="paymentStatus" class="hidden mb-4 p-3 rounded-xl text-sm"></div>
        <div class="flex items-center gap-3 mt-4">
            <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')"
                class="flex-1 py-2.5 border border-gray-300 hover:border-gray-400 text-gray-700 rounded-xl text-sm font-medium transition-colors">
                Cancel
            </button>
            <button type="button" id="payNowBtn"
                class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-colors">
                Pay Now
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var selectedPlan   = null;
var selectedBilling = 'monthly';

/* Called from Alpine @click — yearly is the component's reactive variable */
function openCheckout(planId, planName, monthlyPaise, yearlyPaise, isYearly) {
    selectedPlan    = { id: planId, name: planName, monthly: monthlyPaise, yearly: yearlyPaise };
    selectedBilling = isYearly ? 'yearly' : 'monthly';

    var amount = isYearly ? yearlyPaise : monthlyPaise;
    var cycle  = isYearly ? 'Yearly' : 'Monthly';
    document.getElementById('modalPlanName').textContent =
        planName + ' — ' + cycle + ' ₹' + (amount / 100).toLocaleString('en-IN');
    document.getElementById('paymentModal').classList.remove('hidden');
    document.getElementById('paymentStatus').classList.add('hidden');
    document.getElementById('payNowBtn').textContent = 'Pay Now';
    document.getElementById('payNowBtn').disabled    = false;
}

document.getElementById('payNowBtn').addEventListener('click', function () {
    if (!selectedPlan) return;
    var btn = this;
    btn.textContent = 'Creating order…';
    btn.disabled    = true;

    fetch('/dashboard/subscription/checkout/' + selectedPlan.id, {
        method:  'POST',
        headers: {
            'Content-Type':  'application/json',
            'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ billing_cycle: selectedBilling }),
    })
    .then(function(r) {
        if (!r.ok) throw new Error('Server error: ' + r.status);
        return r.json();
    })
    .then(function(data) {
        if (data.error) throw new Error(data.error);

        var options = {
            key:         data.key,
            amount:      data.amount,
            currency:    data.currency,
            name:        'LabMS',
            description: data.plan + ' subscription',
            order_id:    data.order_id,
            handler: function(response) {
                verifyPayment(response, selectedPlan.id, selectedBilling);
            },
            prefill: {},
            theme: { color: '#4f46e5' },
            modal: {
                ondismiss: function() {
                    btn.textContent = 'Pay Now';
                    btn.disabled    = false;
                }
            }
        };

        var rzp = new Razorpay(options);
        rzp.on('payment.failed', function(res) {
            showStatus('error', 'Payment failed: ' + (res.error.description || 'Unknown error'));
            btn.textContent = 'Pay Now';
            btn.disabled    = false;
        });
        rzp.open();
        btn.textContent = 'Pay Now';
        btn.disabled    = false;
    })
    .catch(function(err) {
        showStatus('error', err.message || 'Something went wrong. Please try again.');
        btn.textContent = 'Pay Now';
        btn.disabled    = false;
    });
});

function verifyPayment(response, planId, billingCycle) {
    showStatus('info', 'Verifying payment…');
    fetch('/dashboard/subscription/verify', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
            razorpay_order_id:   response.razorpay_order_id,
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_signature:  response.razorpay_signature,
            plan_id:             planId,
            billing_cycle:       billingCycle,
        }),
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            showStatus('success', 'Subscription activated! Redirecting…');
            setTimeout(function() { window.location.href = data.redirect; }, 1500);
        } else {
            showStatus('error', data.message || 'Verification failed. Contact support.');
        }
    })
    .catch(function() {
        showStatus('error', 'Verification request failed. Please contact support.');
    });
}

function showStatus(type, msg) {
    var el  = document.getElementById('paymentStatus');
    var map = {
        success: 'bg-emerald-50 text-emerald-800 border border-emerald-200',
        error:   'bg-red-50 text-red-800 border border-red-200',
        info:    'bg-blue-50 text-blue-800 border border-blue-200',
    };
    el.textContent = msg;
    el.className   = 'mb-4 p-3 rounded-xl text-sm ' + (map[type] || map.info);
    el.classList.remove('hidden');
}
</script>
@endpush
@endsection
