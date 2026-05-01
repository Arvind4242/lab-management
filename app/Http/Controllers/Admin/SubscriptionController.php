<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $subscriptions = Subscription::with(['user', 'lab', 'plan'])
            ->when($request->search, fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$request->search}%")))
            ->when($request->status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->latest()
            ->paginate(20);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['user', 'lab', 'plan']);
        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function toggle(Subscription $subscription)
    {
        $subscription->update(['is_active' => !$subscription->is_active]);
        $status = $subscription->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Subscription {$status}.");
    }
}
