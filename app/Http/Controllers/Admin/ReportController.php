<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = Report::with(['user', 'panel'])
            ->when($request->search, fn ($q) => $q->where('patient_name', 'like', "%{$request->search}%"))
            ->when($request->date, fn ($q) => $q->whereDate('test_date', $request->date))
            ->latest()
            ->paginate(20);

        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load([
            'user',
            'panel.category',
            'results' => fn ($q) => $q->orderBy('display_order')->orderBy('id'),
            'results.test.category',
        ]);

        return view('admin.reports.show', compact('report'));
    }
}
