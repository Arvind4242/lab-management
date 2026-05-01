<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Test;
use App\Models\TestCategory;
use App\Models\TestPackage;
use App\Models\TestPanel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = Report::where('user_id', Auth::id())
            ->with('panel')
            ->when($request->search, fn ($q) => $q->where('patient_name', 'like', "%{$request->search}%"))
            ->when($request->date, fn ($q) => $q->whereDate('test_date', $request->date))
            ->latest()
            ->paginate(15);

        return view('user.reports.index', compact('reports'));
    }

    public function create()
    {
        $categories      = TestCategory::all();
        $panels          = TestPanel::pluck('name', 'id');
        $panelCategories = TestPanel::pluck('category_id', 'id')->toArray();
        $tests           = Test::all();

        $myPackages = TestPackage::forUser(Auth::id())
            ->withCount('tests')->latest()->get();
        $globalPackages = TestPackage::global()
            ->withCount('tests')->get();

        return view('user.reports.create', compact(
            'categories', 'panels', 'panelCategories', 'tests', 'myPackages', 'globalPackages'
        ));
    }

    public function store(Request $request)
    {
        $report = Report::create(array_merge(
            $request->only(['patient_name', 'age', 'gender', 'referred_by', 'client_name', 'test_date', 'test_panel_id']),
            ['user_id' => Auth::id()]
        ));

        foreach ($request->input('tests', []) as $index => $testData) {
            $report->report_results()->create([
                'report_test_id'  => $testData['test_id']        ?? null,
                'test_name'       => $testData['test_name']      ?? null,
                'parameter_name'  => $testData['parameter_name'] ?? null,
                'value'           => $testData['value']          ?? null,
                'unit'            => $testData['unit']           ?? null,
                'reference_range' => $testData['reference_range'] ?? null,
                'display_order'   => $index,
            ]);
        }

        return redirect()->route('user.reports.index')->with('success', 'Report saved successfully!');
    }

    public function show(Report $report)
    {
        $this->authorizeReport($report);
        $report->load([
            'user', 'panel.category',
            'results' => fn ($q) => $q->orderBy('display_order')->orderBy('id'),
            'results.test.category',
        ]);
        return view('user.reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        $this->authorizeReport($report);
        $report->load('report_results.test');
        $categories = TestCategory::all();
        $panels = TestPanel::pluck('name', 'id');
        $panelCategories = TestPanel::pluck('category_id', 'id')->toArray();
        $tests = Test::all();

        return view('user.reports.edit', compact('report', 'categories', 'panels', 'panelCategories', 'tests'));
    }

    public function update(Request $request, Report $report)
    {
        $this->authorizeReport($report);

        $report->update($request->only(['patient_name', 'age', 'gender', 'referred_by', 'client_name', 'test_date']));

        foreach ($request->input('tests', []) as $testData) {
            if (!empty($testData['id'])) {
                $report->report_results()->where('id', $testData['id'])->update([
                    'value' => $testData['value'] ?? null,
                ]);
            }
        }

        return redirect()->route('user.reports.show', $report)->with('success', 'Report updated successfully!');
    }

    public function destroy(Report $report)
    {
        $this->authorizeReport($report);
        $report->delete();
        return redirect()->route('user.reports.index')->with('success', 'Report deleted.');
    }

    public function print(Report $report)
    {
        $this->authorizeReport($report);
        $report->load([
            'user', 'panel.category',
            'results' => fn ($q) => $q->orderBy('display_order')->orderBy('id'),
            'results.test.category',
        ]);
        return view('reports.print', compact('report'));
    }

    public function download(Report $report)
    {
        $this->authorizeReport($report);
        $report->load([
            'user', 'panel.category',
            'results' => fn ($q) => $q->orderBy('display_order')->orderBy('id'),
            'results.test.category',
        ]);

        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $report->patient_name ?? 'Report');
        return Pdf::loadView('reports.print', compact('report'))
            ->setPaper('A4', 'portrait')
            ->download($safeName . '_report.pdf');
    }

    public function getPanelTests($panelId)
    {
        $tests = TestPanel::getTestsById($panelId);
        return response()->json($tests);
    }

    public function getSingleTest($id)
    {
        $test = Test::with(['unit', 'category'])->findOrFail($id);

        return response()->json([
            'id'                    => $test->id,
            'name'                  => $test->name,
            'unit'                  => $test->unit?->name ?? '',
            'reference_range_male'  => $test->default_result,
            'reference_range_female'=> $test->default_result_female,
            'reference_range_other' => $test->default_result_other,
            'category'              => $test->category?->name,
        ]);
    }

    private function authorizeReport(Report $report): void
    {
        if ($report->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
