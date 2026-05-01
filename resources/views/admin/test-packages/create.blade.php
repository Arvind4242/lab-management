@extends('layouts.admin')
@section('title','Add Package')
@section('page-title','Add Test Package')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.test-packages.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Package Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Fee (₹)</label>
                    <input type="number" name="fee" value="{{ old('fee') }}" step="0.01" min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Gender</label>
                    <select name="gender" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">All</option>
                        <option value="male"   {{ old('gender')=='male'   ?'selected':'' }}>Male</option>
                        <option value="female" {{ old('gender')=='female' ?'selected':'' }}>Female</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Included Tests</label>
                <div class="border border-gray-200 rounded-xl p-3 max-h-64 overflow-y-auto space-y-1">
                    @foreach($tests->groupBy(fn($t)=>$t->category?->name ?? 'Uncategorized') as $catName => $catTests)
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-1 pt-2 pb-1">{{ $catName }}</p>
                    @foreach($catTests as $test)
                    <label class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg hover:bg-gray-50 cursor-pointer text-sm text-gray-700">
                        <input type="checkbox" name="tests[]" value="{{ $test->id }}" {{ in_array($test->id, old('tests',[]))?'checked':'' }} class="rounded text-indigo-600 focus:ring-indigo-500">
                        {{ $test->name }}
                    </label>
                    @endforeach
                    @endforeach
                </div>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">Create Package</button>
                <a href="{{ route('admin.test-packages.index') }}" class="px-5 py-2.5 text-gray-600 text-sm font-medium">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
