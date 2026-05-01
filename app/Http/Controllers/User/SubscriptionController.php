<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class SubscriptionController extends Controller
{
    private function razorpay(): Api
    {
        return new Api(config('razorpay.key_id'), config('razorpay.key_secret'));
    }

    public function index()
    {
        $subscription = Subscription::where('user_id', auth()->id())
            ->with(['plan', 'lab'])
            ->latest()
            ->first();

        return view('user.subscription.index', compact('subscription'));
    }

    public function plans()
    {
        $plans = Plan::where('is_active', true)->get();
        $currentSub = Subscription::where('user_id', auth()->id())->where('is_active', true)->latest()->first();

        return view('user.subscription.plans', compact('plans', 'currentSub'));
    }

    public function checkout(Request $request, Plan $plan)
    {
        $request->validate(['billing_cycle' => 'required|in:monthly,yearly']);

        $amount = $request->billing_cycle === 'yearly'
            ? (int) ($plan->price_yearly * 100)
            : (int) ($plan->price_monthly * 100);

        if ($amount === 0) {
            return response()->json(['error' => 'This plan is free. No payment needed.'], 400);
        }

        $api = $this->razorpay();
        $order = $api->order->create([
            'amount'   => $amount,
            'currency' => config('razorpay.currency', 'INR'),
            'receipt'  => 'sub_' . auth()->id() . '_' . time(),
            'notes'    => [
                'plan_id'       => $plan->id,
                'user_id'       => auth()->id(),
                'billing_cycle' => $request->billing_cycle,
            ],
        ]);

        return response()->json([
            'order_id' => $order['id'],
            'amount'   => $amount,
            'currency' => config('razorpay.currency', 'INR'),
            'key'      => config('razorpay.key_id'),
            'plan'     => $plan->name,
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_order_id'   => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature'  => 'required|string',
            'plan_id'             => 'required|exists:plans,id',
            'billing_cycle'       => 'required|in:monthly,yearly',
        ]);

        $api = $this->razorpay();

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Payment verification failed.'], 400);
        }

        $plan = Plan::findOrFail($request->plan_id);
        $user = auth()->user();

        // Deactivate old subscriptions
        Subscription::where('user_id', $user->id)->update(['is_active' => false]);

        $endDate = $request->billing_cycle === 'yearly' ? now()->addYear() : now()->addMonth();

        Subscription::create([
            'user_id'              => $user->id,
            'lab_id'               => $user->lab_id,
            'plan_id'              => $plan->id,
            'plan'                 => 'monthly',
            'billing_cycle'        => $request->billing_cycle,
            'start_date'           => now(),
            'end_date'             => $endDate,
            'is_active'            => true,
            'razorpay_order_id'    => $request->razorpay_order_id,
            'razorpay_payment_id'  => $request->razorpay_payment_id,
            'razorpay_signature'   => $request->razorpay_signature,
        ]);

        return response()->json(['success' => true, 'redirect' => route('user.subscription')]);
    }
}
