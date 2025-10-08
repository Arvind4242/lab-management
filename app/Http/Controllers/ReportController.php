<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\TestPanel;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{

 public function create()
    {
        $panels = TestPanel::pluck('name', 'id');
        return view('reports.create', compact('panels'));
    }

    // AJAX: fetch tests from a panel
    public function getPanelTests($panelId)
    {
        $tests = TestPanel::getTestsById($panelId);
        return response()->json($tests);
    }

    public function store(Request $request)
    {
        $report = Report::create($request->only([
            'patient_name', 'age', 'gender', 'referred_by', 'client_name', 'test_date'
        ]));

        foreach ($request->input('tests', []) as $testData) {
    $report->report_results()->create([
        'report_test_id' => $testData['report_test_id'],
        'test_name' => $testData['test_name'] ?? '',
        'value' => $testData['value'] ?? null,
    ]);
}


        return redirect()->route('reports.print', $report->id)
                         ->with('success', 'Report created successfully!');
    }


    public function edit(Report $report)
{
    // Load all related results with their tests and units (if available)
    $report->load(['report_results.report_test', 'report_results.report_test.test_unit']);

    return view('reports.edit', compact('report'));
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
    public function print(Report $report)
    {
        return view('reports.print', compact('report'));
    }


    // 📄 Download report as PDF
    public function download(Report $report)
    {
        $pdf = PDF::loadView('reports.print', compact('report'));
        return $pdf->download('Lab_Report_' . $report->id . '.pdf');
    }
}
