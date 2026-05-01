@extends('layouts.admin')
@section('title','Add Plan')
@section('page-title','Add Subscription Plan')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.plans.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Plan Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Professional"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug *</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" required placeholder="e.g. professional"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Monthly Price (₹) *</label>
                    <input type="number" name="price_monthly" value="{{ old('price_monthly', 0) }}" required min="0" step="0.01"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Yearly Price (₹) *</label>
                    <input type="number" name="price_yearly" value="{{ old('price_yearly', 0) }}" required min="0" step="0.01"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Max Reports/month <span class="text-gray-400 text-xs">(-1 = unlimited)</span></label>
                    <input type="number" name="max_reports" value="{{ old('max_reports', -1) }}" required min="-1"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Max Users <span class="text-gray-400 text-xs">(-1 = unlimited)</span></label>
                    <input type="number" name="max_users" value="{{ old('max_users', -1) }}" required min="-1"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Features <span class="text-gray-400 text-xs">(one per line)</span></label>
                    <textarea name="features_text" rows="5" placeholder="200 Reports/month&#10;5 Users&#10;PDF Reports&#10;Priority Support"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ old('features_text') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Razorpay Plan ID <span class="text-gray-400 text-xs">(optional)</span></label>
                    <input type="text" name="razorpay_plan_id" value="{{ old('razorpay_plan_id') }}" placeholder="plan_xxxx"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="flex items-end pb-0.5">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-medium text-gray-700">Active (visible to users)</span>
                    </label>
                </div>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">Create Plan</button>
                <a href="{{ route('admin.plans.index') }}" class="px-5 py-2.5 text-gray-600 text-sm font-medium">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
