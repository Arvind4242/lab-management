<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $units = Unit::when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->withCount('tests')
            ->latest()
            ->paginate(20);

        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:units,name']);
        Unit::create($request->only('name'));
        return redirect()->route('admin.units.index')->with('success', 'Unit created.');
    }

    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate(['name' => 'required|string|max:100|unique:units,name,' . $unit->id]);
        $unit->update($request->only('name'));
        return redirect()->route('admin.units.index')->with('success', 'Unit updated.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('admin.units.index')->with('success', 'Unit deleted.');
    }
}
