<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestCategory;
use App\Models\TestPanel;
use Illuminate\Http\Request;

class TestPanelController extends Controller
{
    public function index(Request $request)
    {
        $panels = TestPanel::with('category')
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(20);

        return view('admin.test-panels.index', compact('panels'));
    }

    public function create()
    {
        $categories = TestCategory::all();
        $tests = Test::with('category')->get();
        return view('admin.test-panels.create', compact('categories', 'tests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'nullable|exists:test_categories,id',
        ]);

        TestPanel::create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'tests'       => $request->input('tests', []),
        ]);

        return redirect()->route('admin.test-panels.index')->with('success', 'Panel created.');
    }

    public function edit(TestPanel $testPanel)
    {
        $categories = TestCategory::all();
        $tests = Test::with('category')->get();
        $selectedTests = $testPanel->tests ?? [];
        return view('admin.test-panels.edit', compact('testPanel', 'categories', 'tests', 'selectedTests'));
    }

    public function update(Request $request, TestPanel $testPanel)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'nullable|exists:test_categories,id',
        ]);

        $testPanel->update([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'tests'       => $request->input('tests', []),
        ]);

        return redirect()->route('admin.test-panels.index')->with('success', 'Panel updated.');
    }

    public function destroy(TestPanel $testPanel)
    {
        $testPanel->delete();
        return redirect()->route('admin.test-panels.index')->with('success', 'Panel deleted.');
    }
}
