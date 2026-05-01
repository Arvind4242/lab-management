<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestCategory;
use Illuminate\Http\Request;

class TestCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = TestCategory::withCount('tests')
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(20);

        return view('admin.test-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.test-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:test_categories,name']);
        TestCategory::create($request->only('name'));
        return redirect()->route('admin.test-categories.index')->with('success', 'Category created.');
    }

    public function edit(TestCategory $testCategory)
    {
        return view('admin.test-categories.edit', compact('testCategory'));
    }

    public function update(Request $request, TestCategory $testCategory)
    {
        $request->validate(['name' => 'required|string|max:255|unique:test_categories,name,' . $testCategory->id]);
        $testCategory->update($request->only('name'));
        return redirect()->route('admin.test-categories.index')->with('success', 'Category updated.');
    }

    public function destroy(TestCategory $testCategory)
    {
        $testCategory->delete();
        return redirect()->route('admin.test-categories.index')->with('success', 'Category deleted.');
    }
}
