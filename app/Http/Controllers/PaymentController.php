<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;
use Exception;

class PaymentController extends Controller
{
    public function redirectFlow()
    {
        if (Auth::check()) {
            // User exists & logged in → go to payment
            return redirect()->route('payment.page');
        }

        // User not exists/not logged in → go to sign in
        return redirect()->route('login');
    }

    public function index()
    {
        return view('package');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        if (isset($input['razorpay_payment_id'])) {
            try {
                $payment = $api->payment->fetch($input['razorpay_payment_id']);
                $api->payment->fetch($input['razorpay_payment_id'])
                    ->capture(['amount' => $payment['amount']]);

                Session::put('success', 'Payment Successful ✅');
                return redirect()->route('home');
            } catch (Exception $e) {
                Session::put('error', $e->getMessage());
                return redirect()->route('home');
            }
        }

        return redirect()->route('home');
    }
}
