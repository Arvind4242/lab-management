<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestCategory;
use App\Models\Unit;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $tests = Test::with(['category', 'unit'])
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->category_id, fn ($q) => $q->where('category_id', $request->category_id))
            ->latest()
            ->paginate(20);

        $categories = TestCategory::all();
        return view('admin.tests.index', compact('tests', 'categories'));
    }

    public function create()
    {
        $categories = TestCategory::all();
        $units = Unit::all();
        return view('admin.tests.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:test_categories,id',
            'unit_id'     => 'nullable|exists:units,id',
        ]);

        Test::create($request->only([
            'name', 'category_id', 'unit_id', 'default_result',
            'default_result_female', 'default_result_other', 'test_group', 'price',
        ]));

        return redirect()->route('admin.tests.index')->with('success', 'Test created successfully.');
    }

    public function edit(Test $test)
    {
        $categories = TestCategory::all();
        $units = Unit::all();
        return view('admin.tests.edit', compact('test', 'categories', 'units'));
    }

    public function update(Request $request, Test $test)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:test_categories,id',
            'unit_id'     => 'nullable|exists:units,id',
        ]);

        $test->update($request->only([
            'name', 'category_id', 'unit_id', 'default_result',
            'default_result_female', 'default_result_other', 'test_group', 'price',
        ]));

        return redirect()->route('admin.tests.index')->with('success', 'Test updated.');
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('admin.tests.index')->with('success', 'Test deleted.');
    }
}
