<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Report;
use App\Models\TestPanel;
use App\Models\TestCategory;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\Snappy\Facades\SnappyPdf;

class ReportController extends Controller
{

public function create()
{
    $categories = TestCategory::all();
    $panels = TestPanel::pluck('name', 'id');
    $panelCategories = TestPanel::pluck('category_id', 'id'); // Map panel ID to category ID
    $tests = Test::all();

    return view('reports.create', compact('categories', 'panels', 'panelCategories', 'tests'));
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
    // Create the report with authenticated user
    $report = Report::create(array_merge(
        $request->only([
            'patient_name',
            'age',
            'gender',
            'referred_by',
            'client_name',
            'test_date',
            'test_panel_id',
        ]),
        ['user_id' => Auth::id()] // add the logged-in user's ID
    ));

    // Save test results in the SAME drag order
    foreach ($request->input('tests', []) as $index => $testData) {
        $report->report_results()->create([
            'report_test_id'   => $testData['test_id']        ?? null,
            'test_name'        => $testData['test_name']      ?? null,
            'parameter_name'   => $testData['parameter_name'] ?? null,
            'value'            => $testData['value']          ?? null,
            'unit'             => $testData['unit']           ?? null,
            'reference_range'  => $testData['reference_range'] ?? null,

            // ✅ yahan drag & drop ka order aa raha hai
            'display_order'    => $index,
        ]);
    }

    return redirect()->back()->with('success', 'Report saved successfully!');
}


//    public function edit(Report $report)
// {
//     // Load all related results with their tests and units (if available)
//     $report->load(['report_results.report_test', 'report_results.report_test.test_unit']);

//     // Get all test panels for the dropdown
//     $panels = \App\Models\TestPanel::pluck('name', 'id');

//     // Pass both report and panels to the view
//     return view('reports.edit', compact('report', 'panels'));
// }

public function edit($id)
{
    $report = Report::with('report_results.test')->findOrFail($id);
    $categories = TestCategory::all();
    $panels = TestPanel::pluck('name', 'id');
    $panelCategories = TestPanel::pluck('category_id', 'id')->toArray();
    $tests = Test::all();

    return view('reports.edit', compact('report', 'categories', 'panels', 'panelCategories', 'tests'));
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
    $patientName = $report->patient->name ?? 'Unknown_Patient';
    $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $patientName);

    $pdf = \PDF::loadView('reports.print', compact('report'))
        ->setPaper('a4')
        ->setOption('margin-top', 5)
        ->setOption('margin-bottom', 5)
        ->setOption('margin-left', 5)
        ->setOption('margin-right', 5);

    return $pdf->download($safeName . '_report.pdf');
}


//    public function download(Report $report)
// {
    // $pdf = PDF::loadView('reports.print', compact('report'))
    //           ->setPaper('A4', 'portrait')
    //           ->setOptions([
    //               'dpi' => 150,
    //               'defaultFont' => 'DejaVu Sans',
    //               'isHtml5ParserEnabled' => true,
    //               'isRemoteEnabled' => true,
    //           ]);

//     return $pdf->download('Lab_Report_' . $report->id . '.pdf');
// }

}
