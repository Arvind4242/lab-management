@extends('layouts.user')
@section('title', 'Edit Package')
@section('page-title', 'Edit Package')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('user.packages.update', $package) }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Package Name *</label>
                    <input type="text" name="name" value="{{ old('name', $package->name) }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <input type="text" name="description" value="{{ old('description', $package->description) }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Fee (₹)</label>
                    <input type="number" name="fee" value="{{ old('fee', $package->fee) }}" min="0" step="0.01"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div x-data="testPicker()" class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-medium text-gray-700">Select Tests * <span class="text-gray-400 font-normal" x-text="'(' + selected.length + ' selected)'"></span></label>
                    <button type="button" @click="selectAll()" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">Select all</button>
                </div>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="search" placeholder="Search tests…"
                        class="w-full pl-9 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="max-h-72 overflow-y-auto divide-y divide-gray-50">
                        @foreach($tests->groupBy(fn($t) => $t->category?->name ?? 'Uncategorized') as $catName => $catTests)
                        <div x-show="categoryVisible('{{ addslashes($catName) }}', {{ json_encode($catTests->pluck('name')->toArray()) }})">
                            <div class="px-4 py-2 bg-gray-50 sticky top-0">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $catName }}</span>
                            </div>
                            @foreach($catTests as $test)
                            <label x-show="visible('{{ addslashes($test->name) }}')"
                                   class="flex items-center gap-3 px-4 py-2.5 hover:bg-indigo-50 cursor-pointer transition-colors"
                                   :class="isSelected({{ $test->id }}) ? 'bg-indigo-50' : ''">
                                <input type="checkbox" name="tests[]" value="{{ $test->id }}"
                                    x-model="selected" :value="{{ $test->id }}"
                                    class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 flex-shrink-0">
                                <span class="text-sm text-gray-800 flex-1">{{ $test->name }}</span>
                                @if($test->unit)
                                <span class="text-xs text-gray-400">{{ $test->unit->name }}</span>
                                @endif
                            </label>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
                @error('tests')<p class="text-xs text-red-600">{{ $message }}</p>@enderror

                <div x-show="selected.length > 0" x-cloak class="flex flex-wrap gap-1.5 p-3 bg-indigo-50 rounded-xl">
                    <span class="text-xs font-semibold text-indigo-700 mr-1 self-center">Selected:</span>
                    <template x-for="id in selected" :key="id">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-white border border-indigo-200 text-indigo-700 text-xs rounded-full">
                            <span x-text="testName(id)"></span>
                            <button type="button" @click="deselect(id)" class="text-indigo-400 hover:text-indigo-700">×</button>
                        </span>
                    </template>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors">Save Changes</button>
                <a href="{{ route('user.packages.index') }}" class="px-5 py-2.5 text-gray-600 text-sm font-medium">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const allTests = @json($tests->map(fn($t) => ['id' => $t->id, 'name' => $t->name]));

function testPicker() {
    return {
        search: '',
        selected: @json(old('tests', $selectedTests)).map(Number),
        visible(name) { return !this.search || name.toLowerCase().includes(this.search.toLowerCase()); },
        categoryVisible(cat, names) {
            if (!this.search) return true;
            return names.some(n => n.toLowerCase().includes(this.search.toLowerCase()));
        },
        isSelected(id) { return this.selected.includes(id); },
        deselect(id) { this.selected = this.selected.filter(s => s !== id); },
        selectAll() { this.selected = allTests.map(t => t.id); },
        testName(id) { return allTests.find(t => t.id === id)?.name ?? id; },
    };
}
</script>
@endpush
@endsection
