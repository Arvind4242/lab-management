<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestPackage;
use Illuminate\Http\Request;

class TestPackageController extends Controller
{
    public function index(Request $request)
    {
        $packages = TestPackage::withCount('tests')
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(20);

        return view('admin.test-packages.index', compact('packages'));
    }

    public function create()
    {
        $tests = Test::with('category')->get();
        return view('admin.test-packages.create', compact('tests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fee'  => 'nullable|numeric|min:0',
        ]);

        $package = TestPackage::create($request->only('name', 'fee', 'gender'));
        $package->tests()->sync($request->input('tests', []));

        return redirect()->route('admin.test-packages.index')->with('success', 'Package created.');
    }

    public function edit(TestPackage $testPackage)
    {
        $tests = Test::with('category')->get();
        $selectedTests = $testPackage->tests()->pluck('tests.id')->toArray();
        return view('admin.test-packages.edit', compact('testPackage', 'tests', 'selectedTests'));
    }

    public function update(Request $request, TestPackage $testPackage)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fee'  => 'nullable|numeric|min:0',
        ]);

        $testPackage->update($request->only('name', 'fee', 'gender'));
        $testPackage->tests()->sync($request->input('tests', []));

        return redirect()->route('admin.test-packages.index')->with('success', 'Package updated.');
    }

    public function destroy(TestPackage $testPackage)
    {
        $testPackage->delete();
        return redirect()->route('admin.test-packages.index')->with('success', 'Package deleted.');
    }
}
