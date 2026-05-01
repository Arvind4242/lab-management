@extends('layouts.admin')
@section('title','Edit Lab')
@section('page-title','Edit Lab')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.labs.update', $lab) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Lab Name *</label>
                <input type="text" name="name" value="{{ old('name', $lab->name) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Address</label>
                <textarea name="address" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none resize-none">{{ old('address', $lab->address) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $lab->phone) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Owner</label>
                <select name="user_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">— Select user —</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ old('user_id',$lab->user_id)==$u->id?'selected':'' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">Save Changes</button>
                <a href="{{ route('admin.labs.index') }}" class="px-5 py-2.5 text-gray-600 text-sm font-medium">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
