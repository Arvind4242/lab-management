<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\TestPanel;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{

 public function create()
    {
        $panels = TestPanel::pluck('name', 'id');
        $tests = \App\Models\Test::select('id', 'name')->get(); // 👈 added
    return view('reports.create', compact('panels', 'tests'));
    }

    // AJAX: fetch tests from a panel
    public function getPanelTests($panelId)
    {
        $tests = TestPanel::getTestsById($panelId);
        return response()->json($tests);
    }

 public function getSingleTest($id)
{
    Log::info('Fetching single test details', ['test_id' => $id]);

    $test = \App\Models\Test::with(['unit', 'category'])->find($id);

    if (!$test) {
        Log::warning('Test not found', ['test_id' => $id]);
        return response()->json(['error' => 'Test not found'], 404);
    }

    Log::info('Test details fetched', [
        'id' => $test->id,
        'name' => $test->name,
        'unit' => $test->unit ? $test->unit->name : null,
        'reference_male_range' => $test->default_result,
        'reference_female_range' => $test->default_result_female,
        'reference_other_range' => $test->default_result_other,
        'category' => $test->category ? $test->category->name : null,
    ]);

    return response()->json([
        'id' => $test->id,
        'name' => $test->name,
        'unit' => $test->unit ? $test->unit->name : '',
        'reference_range_male' => $test->default_result,
        'reference_range_female' => $test->default_result_female,
        'reference_range_other' => $test->default_result_other,
        'category' => $test->category ? $test->category->name : null,
    ]);
}




    public function store(Request $request)
    {
        // dd($request->all());

        // Create the report with authenticated user
        $report = Report::create(array_merge(
            $request->only([
                'patient_name', 'age', 'gender', 'referred_by', 'client_name', 'test_date', 'test_panel_id',
            ]),
            ['user_id' => Auth::id()] // add the logged-in user's ID
        ));

        // Save test results
        foreach ($request->input('tests', []) as $testData) {
            $report->report_results()->create([
                'report_test_id'    => $testData['test_id'] ?? null,
                'test_name'         => $testData['test_name'] ?? null,
                'parameter_name'    => $testData['parameter_name'] ?? null,
                'value'             => $testData['value'] ?? null,
                'unit'              => $testData['unit'] ?? null,
                'reference_range'   => $testData['reference_range'] ?? null,
            ]);
        }

          return redirect()->back()->with('success', 'Report saved successfully!');
    }


   public function edit(Report $report)
{
    // Load all related results with their tests and units (if available)
    $report->load(['report_results.report_test', 'report_results.report_test.test_unit']);

    // Get all test panels for the dropdown
    $panels = \App\Models\TestPanel::pluck('name', 'id');

    // Pass both report and panels to the view
    return view('reports.edit', compact('report', 'panels'));
}


public function update(Request $request, Report $report)
{
    // Update basic report info
    $report->update($request->only([
        'patient_name', 'age', 'gender', 'referred_by', 'client_name', 'test_date'
    ]));

    // Update each test result
    foreach ($request->input('tests', []) as $testData) {
        if (!empty($testData['id'])) {
            $report->report_results()->where('id', $testData['id'])->update([
                'value' => $testData['value'] ?? null,
            ]);
        }
    }

    return redirect()->route('reports.print', $report->id)
        ->with('success', 'Report updated successfully!');
}


    // 🖨️ Preview report in print-friendly format


public function print($reportId)
{
    // Fetch the report along with related panel and results
    // $report = Report::with(['panel', 'results'])->findOrFail($reportId);

 $report = Report::with([
    'panel.category',        // ✅ load TestPanel + TestCategory
    'results.test.category', // ✅ load Test + TestCategory for results
])->findOrFail($reportId);

// \Log::info('Report Panel Debug:', [
//     'panel' => $report->panel,
//     'panel_id' => $report->test_panel_id,
// ]);



    // Log the data
    \Log::info('Report data fetched for print:', $report->toArray());

    return view('reports.print', compact('report'));
}

public function testView($reportId)
{
    $report = Report::with([
        'panel.category',
        'results.test.category',
    ])->findOrFail($reportId);

    return view('reports.print', compact('report'));
}

    // 📄 Download report as PDF
    public function download(Report $report)
    {
        $pdf = PDF::loadView('reports.print', compact('report'));
        return $pdf->download('Lab_Report_' . $report->id . '.pdf');
    }

//    public function download(Report $report)
// {
//     $pdf = PDF::loadView('reports.print', compact('report'))
//               ->setPaper('A4', 'portrait')
//               ->setOptions([
//                   'dpi' => 150,
//                   'defaultFont' => 'DejaVu Sans',
//                   'isHtml5ParserEnabled' => true,
//                   'isRemoteEnabled' => true,
//               ]);

//     return $pdf->download('Lab_Report_' . $report->id . '.pdf');
// }

}
