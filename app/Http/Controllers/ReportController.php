<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Report;
use App\Models\TestPanel;
use App\Models\TestCategory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{

// public function create()
// {
//     $categories = TestCategory::all();
//     $panels = TestPanel::pluck('name', 'id');
//     $panelCategories = TestPanel::pluck('category_id', 'id'); // Map panel ID to category ID
//     $tests = Test::all();

//     return view('reports.create', compact('categories', 'panels', 'panelCategories', 'tests'));
// }


public function create()
{
    $categories = TestCategory::all();
    $panels = TestPanel::pluck('name', 'id');
    $panelCategories = TestPanel::pluck('category_id', 'id')->toArray();
    $tests = Test::all();

    return view('filament.admin.pages.create-report', [
        'categories' => $categories,
        'panels' => $panels,
        'panelCategories' => $panelCategories,
        'tests' => $tests,
    ]);
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
            'interpretation'   => $testData['interpretation']  ?? null,
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
        $resultId = $testData['result_id'] ?? null;
        if ($resultId) {
            $report->report_results()->where('id', $resultId)->update([
                'value'          => $testData['value']          ?? null,
                'interpretation' => $testData['interpretation'] ?? null,
            ]);
        }
    }

    return redirect()->route('reports.print', $report->id)
        ->with('success', 'Report updated successfully!');
}


    // ── Theme helpers ────────────────────────────────────────────────

    private function lightenHex(string $hex, float $amount): string
    {
        $hex = ltrim($hex, '#');
        $r = (int)(hexdec(substr($hex, 0, 2)) + (255 - hexdec(substr($hex, 0, 2))) * $amount);
        $g = (int)(hexdec(substr($hex, 2, 2)) + (255 - hexdec(substr($hex, 2, 2))) * $amount);
        $b = (int)(hexdec(substr($hex, 4, 2)) + (255 - hexdec(substr($hex, 4, 2))) * $amount);
        return sprintf('#%02x%02x%02x', min(255,$r), min(255,$g), min(255,$b));
    }

    private function darkenHex(string $hex, float $amount): string
    {
        $hex = ltrim($hex, '#');
        $r = (int)(hexdec(substr($hex, 0, 2)) * (1 - $amount));
        $g = (int)(hexdec(substr($hex, 2, 2)) * (1 - $amount));
        $b = (int)(hexdec(substr($hex, 4, 2)) * (1 - $amount));
        return sprintf('#%02x%02x%02x', max(0,$r), max(0,$g), max(0,$b));
    }

    private function getTheme(string $name): array
    {
        $presets = [
            'navy' => [
                'primary'       => '#1a3461',
                'primaryDark'   => '#122548',
                'primaryLite'   => '#e8f0fb',
                'primaryBorder' => '#c2d4ec',
                'primaryTint'   => '#f0f5fc',
                'legendBg'      => '#f2f6fc',
                'legendBorder'  => '#d4e0f0',
                'accent'        => '#e8a020',
            ],
            'teal' => [
                'primary'       => '#0e7490',
                'primaryDark'   => '#0a5b72',
                'primaryLite'   => '#e0f7fc',
                'primaryBorder' => '#b0dce8',
                'primaryTint'   => '#f0fbfd',
                'legendBg'      => '#f0fbfd',
                'legendBorder'  => '#b0dce8',
                'accent'        => '#f59e0b',
            ],
            'emerald' => [
                'primary'       => '#166534',
                'primaryDark'   => '#0e4524',
                'primaryLite'   => '#dcfce7',
                'primaryBorder' => '#a7d4b8',
                'primaryTint'   => '#f0fdf4',
                'legendBg'      => '#f0fdf4',
                'legendBorder'  => '#a7d4b8',
                'accent'        => '#f59e0b',
            ],
            'purple' => [
                'primary'       => '#5b21b6',
                'primaryDark'   => '#4c1d95',
                'primaryLite'   => '#ede9fe',
                'primaryBorder' => '#c4b5fd',
                'primaryTint'   => '#f5f3ff',
                'legendBg'      => '#f5f3ff',
                'legendBorder'  => '#c4b5fd',
                'accent'        => '#f59e0b',
            ],
            'rose' => [
                'primary'       => '#9f1239',
                'primaryDark'   => '#7f0f2e',
                'primaryLite'   => '#ffe4e6',
                'primaryBorder' => '#fca5a5',
                'primaryTint'   => '#fff5f5',
                'legendBg'      => '#fff5f5',
                'legendBorder'  => '#fca5a5',
                'accent'        => '#f59e0b',
            ],
            'slate' => [
                'primary'       => '#1e293b',
                'primaryDark'   => '#0f172a',
                'primaryLite'   => '#f1f5f9',
                'primaryBorder' => '#cbd5e1',
                'primaryTint'   => '#f8fafc',
                'legendBg'      => '#f8fafc',
                'legendBorder'  => '#e2e8f0',
                'accent'        => '#e8a020',
            ],
        ];

        if ($name === 'custom') {
            $hex = ltrim(request('color', '1a3461'), '#');
            if (!preg_match('/^[0-9A-Fa-f]{6}$/', $hex)) $hex = '1a3461';
            $p = '#' . $hex;
            return [
                'primary'       => $p,
                'primaryDark'   => $this->darkenHex($p, 0.20),
                'primaryLite'   => $this->lightenHex($p, 0.88),
                'primaryBorder' => $this->lightenHex($p, 0.65),
                'primaryTint'   => $this->lightenHex($p, 0.94),
                'legendBg'      => $this->lightenHex($p, 0.96),
                'legendBorder'  => $this->lightenHex($p, 0.78),
                'accent'        => '#e8a020',
            ];
        }

        return $presets[$name] ?? $presets['navy'];
    }

    // 🖨️ Preview report in print-friendly format

    public function print($reportId)
    {
        $report = Report::with([
            'user',
            'panel.category',
            'results' => fn ($q) => $q->orderBy('display_order')->orderBy('id'),
            'results.test.category',
        ])->findOrFail($reportId);

        $selectedTheme = request('theme', 'navy');
        $theme = $this->getTheme($selectedTheme);

        \Log::info('Report data fetched for print:', $report->toArray());

        return view('reports.print', compact('report', 'theme', 'selectedTheme'));
    }

    public function testView($reportId)
    {
        $report = Report::with([
            'panel.category',
            'results.test.category',
        ])->findOrFail($reportId);

        $selectedTheme = 'navy';
        $theme = $this->getTheme($selectedTheme);

        return view('reports.print', compact('report', 'theme', 'selectedTheme'));
    }

    // 📄 Download report as PDF
    public function download(Report $report)
    {
        $report->load([
            'user',
            'panel.category',
            'results' => fn ($q) => $q->orderBy('display_order')->orderBy('id'),
            'results.test.category',
        ]);

        $selectedTheme = request('theme', 'navy');
        $theme = $this->getTheme($selectedTheme);

        $patientName = $report->patient_name ?? 'Report';
        $safeName    = preg_replace('/[^A-Za-z0-9_\-]/', '_', $patientName);

        $pdf = PDF::loadView('reports.print', compact('report', 'theme', 'selectedTheme'))
            ->setPaper('A4', 'portrait');

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
