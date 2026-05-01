<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount('subscriptions')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'slug'          => 'required|string|max:100|unique:plans,slug',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly'  => 'required|numeric|min:0',
            'max_reports'   => 'required|integer|min:-1',
            'max_users'     => 'required|integer|min:-1',
        ]);

        $data = $request->only('name', 'slug', 'description', 'price_monthly', 'price_yearly', 'max_reports', 'max_users', 'razorpay_plan_id');
        $data['features'] = array_filter(explode("\n", $request->features_text ?? ''));
        $data['is_active'] = $request->boolean('is_active', true);

        Plan::create($data);

        return redirect()->route('admin.plans.index')->with('success', 'Plan created.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'slug'          => 'required|string|max:100|unique:plans,slug,' . $plan->id,
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly'  => 'required|numeric|min:0',
            'max_reports'   => 'required|integer|min:-1',
            'max_users'     => 'required|integer|min:-1',
        ]);

        $data = $request->only('name', 'slug', 'description', 'price_monthly', 'price_yearly', 'max_reports', 'max_users', 'razorpay_plan_id');
        $data['features'] = array_filter(explode("\n", $request->features_text ?? ''));
        $data['is_active'] = $request->boolean('is_active');

        $plan->update($data);

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted.');
    }
}
