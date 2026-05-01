<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestPackageController extends Controller
{
    public function index(Request $request)
    {
        $myPackages = TestPackage::with('tests')
            ->forUser(Auth::id())
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->withCount('tests')
            ->latest()
            ->get();

        $globalPackages = TestPackage::global()
            ->withCount('tests')
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->get();

        return view('user.packages.index', compact('myPackages', 'globalPackages'));
    }

    public function create()
    {
        $tests = Test::with('category')->orderBy('name')->get();
        return view('user.packages.create', compact('tests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'fee'   => 'nullable|numeric|min:0',
            'tests' => 'required|array|min:1',
        ], [
            'tests.required' => 'Please select at least one test.',
            'tests.min'      => 'Please select at least one test.',
        ]);

        $package = TestPackage::create([
            'name'        => $request->name,
            'description' => $request->description,
            'fee'         => $request->fee,
            'user_id'     => Auth::id(),
        ]);

        $package->tests()->sync($request->input('tests', []));

        return redirect()->route('user.packages.index')->with('success', 'Package "' . $package->name . '" created.');
    }

    public function edit(TestPackage $package)
    {
        $this->authorizePackage($package);
        $tests = Test::with('category')->orderBy('name')->get();
        $selectedTests = $package->tests()->pluck('tests.id')->toArray();
        return view('user.packages.edit', compact('package', 'tests', 'selectedTests'));
    }

    public function update(Request $request, TestPackage $package)
    {
        $this->authorizePackage($package);

        $request->validate([
            'name'  => 'required|string|max:255',
            'fee'   => 'nullable|numeric|min:0',
            'tests' => 'required|array|min:1',
        ], [
            'tests.required' => 'Please select at least one test.',
        ]);

        $package->update([
            'name'        => $request->name,
            'description' => $request->description,
            'fee'         => $request->fee,
        ]);

        $package->tests()->sync($request->input('tests', []));

        return redirect()->route('user.packages.index')->with('success', 'Package updated.');
    }

    public function destroy(TestPackage $package)
    {
        $this->authorizePackage($package);
        $package->delete();
        return redirect()->route('user.packages.index')->with('success', 'Package deleted.');
    }

    public function getTests(TestPackage $package)
    {
        $tests = $package->tests()->with('unit')->get()->map(fn ($t) => [
            'id'                     => $t->id,
            'name'                   => $t->name,
            'unit'                   => $t->unit?->name ?? '',
            'reference_range_male'   => $t->default_result,
            'reference_range_female' => $t->default_result_female,
            'reference_range_other'  => $t->default_result_other,
        ]);

        return response()->json($tests);
    }

    private function authorizePackage(TestPackage $package): void
    {
        if ($package->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own packages.');
        }
    }
}
