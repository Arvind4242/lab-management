<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
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
