@extends('layouts.admin')
@section('title','Edit Test')
@section('page-title','Edit Test')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.tests.update', $test) }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Test Name *</label>
                    <input type="text" name="name" value="{{ old('name', $test->name) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Category *</label>
                    <select name="category_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ old('category_id',$test->category_id)==$c->id?'selected':'' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Unit</label>
                    <select name="unit_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">— No unit —</option>
                        @foreach($units as $u)
                        <option value="{{ $u->id }}" {{ old('unit_id',$test->unit_id)==$u->id?'selected':'' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Ref. Range (Male)</label>
                    <input type="text" name="default_result" value="{{ old('default_result',$test->default_result) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Ref. Range (Female)</label>
                    <input type="text" name="default_result_female" value="{{ old('default_result_female',$test->default_result_female) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Ref. Range (Other)</label>
                    <input type="text" name="default_result_other" value="{{ old('default_result_other',$test->default_result_other) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Test Group</label>
                    <input type="text" name="test_group" value="{{ old('test_group',$test->test_group) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Price (₹)</label>
                    <input type="number" name="price" value="{{ old('price',$test->price) }}" step="0.01" min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">Save Changes</button>
                <a href="{{ route('admin.tests.index') }}" class="px-5 py-2.5 text-gray-600 text-sm font-medium">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
