<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RazorpayController extends Controller
{
    public function webhook(Request $request)
    {
        $webhookSecret = config('razorpay.webhook_secret');
        $webhookSignature = $request->header('X-Razorpay-Signature');
        $webhookBody = $request->getContent();

        if ($webhookSecret) {
            $expectedSignature = hash_hmac('sha256', $webhookBody, $webhookSecret);
            if (!hash_equals($expectedSignature, $webhookSignature ?? '')) {
                Log::warning('Invalid Razorpay webhook signature');
                return response()->json(['status' => 'invalid_signature'], 400);
            }
        }

        $event = $request->input('event');
        $payload = $request->input('payload', []);

        Log::info('Razorpay webhook received', ['event' => $event]);

        match ($event) {
            'payment.captured' => $this->handlePaymentCaptured($payload),
            'subscription.charged' => $this->handleSubscriptionCharged($payload),
            default => null,
        };

        return response()->json(['status' => 'ok']);
    }

    private function handlePaymentCaptured(array $payload): void
    {
        $paymentId = $payload['payment']['entity']['id'] ?? null;
        if ($paymentId) {
            Subscription::where('razorpay_payment_id', $paymentId)->update(['is_active' => true]);
        }
    }

    private function handleSubscriptionCharged(array $payload): void
    {
        Log::info('Subscription charged', $payload);
    }
}
