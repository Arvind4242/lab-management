<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lab Report - {{ $report->patient_name ?? 'N/A' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 20px; background: #fff; color: #333; }
        .container { max-width: 800px; margin: 0 auto; border: 2px solid #000; padding: 0; }

        /* Header */
        header { display: flex; justify-content: space-between; padding: 20px; border-bottom: 2px solid #000; }
        .logo-section { flex: 1; }
        .logo-text { font-size: 48px; font-weight: bold; color: #008B8B; line-height: 1; }
        .logo-text .path { color: #008B8B; }
        .logo-text .number { color: #FDB714; }
        .tagline { color: #FDB714; font-size: 14px; font-style: italic; margin-top: 5px; }
        .lab-details { text-align: right; font-size: 11px; line-height: 1.6; }
        .lab-details strong { font-weight: bold; }

        /* Patient Info */
        .patient-info { padding: 20px; border-bottom: 2px solid #000; }
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; font-size: 13px; }
        .info-item { display: flex; gap: 10px; }
        .info-label { font-weight: bold; min-width: 120px; }
        .info-value { color: #333; }

        /* Test Header */
        .test-header { background: #f5f5f5; padding: 15px 20px; text-align: center; border-bottom: 2px solid #000; }
        .test-header h2 { font-size: 16px; font-weight: bold; margin-bottom: 5px; }
        .test-header h3 { font-size: 14px; font-weight: bold; }

        /* Results Table */
        .results-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .results-table thead { background: #f0f0f0; }
        .results-table th, .results-table td { padding: 10px; text-align: left; font-size: 13px; border-bottom: 1px solid #ddd; }
        .results-table th { font-weight: bold; border-right: 1px solid #ddd; }
        .results-table td { border-right: 1px solid #ddd; }
        .results-table th:last-child, .results-table td:last-child { border-right: none; }

        .result-low { color: #f44336; font-weight: bold; }
        .result-high { color: #e91e63; font-weight: bold; }
        .result-normal { color: #4caf50; font-weight: bold; }

        /* Remarks */
        .remarks-section { padding: 15px 20px; border-top: 1px solid #ddd; font-size: 13px; }
        .remarks-section .label { font-weight: bold; margin-right: 10px; }

        /* Footer */
        .end-report { text-align: center; padding: 15px; font-size: 12px; font-weight: bold; border-top: 2px solid #000; }

        @media print {
            body { padding: 0; }
            .container { border: none; }
            @page { margin: 10mm; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header>
            <div class="logo-section">
                <div class="logo-text">
                    <span class="path">Zalgo</span><span class="number">24</span>
                </div>
                <div class="tagline">Your health guard</div>
                <div style="color: #008B8B; font-weight: bold; margin-top: 5px;">Labs</div>
            </div>
            <div class="lab-details">
                <div><strong>Main Lab :</strong> 14, Geeta Colony, Near Natyakala Mandir</div>
                <div>Hospital Road, Lashkar, Gwalior - 474009 (M.P.)</div>
                <div><strong>Customer Care :</strong> 91119-02424, 0751-4712424</div>
                <div><strong>Reference Lab :</strong> 31, Jaora Compound, In Front of M.Y. Hospital, Gala No. 2, Indore (M.P.)</div>
                <div><strong>E-mail:</strong> path24labsgwl@gmail.com</div>
                <div><strong>Web:</strong> www.path24labs.com</div>
            </div>
        </header>

        <!-- Patient Info -->
        <div class="patient-info">
            <div class="info-grid">
                <div class="info-item"><span class="info-label">Patient Name</span><span class="info-value">: {{ $report->patient_name ?? 'N/A' }}</span></div>
                <div class="info-item"><span class="info-label">Sam.Registered At</span><span class="info-value">: {{ \Carbon\Carbon::parse($report->created_at)->format('d/m/Y h:i A') ?? '-' }}</span></div>
                <div class="info-item"><span class="info-label">Age</span><span class="info-value">: {{ $report->age ?? '-' }}Y / {{ strtoupper($report->gender ?? 'N/A') }}</span></div>
                <div class="info-item"><span class="info-label">Reported At</span><span class="info-value">: {{ \Carbon\Carbon::parse($report->test_date)->format('d/m/Y h:i A') ?? '-' }}</span></div>
                <div class="info-item"><span class="info-label">Referred By</span><span class="info-value">: {{ $report->referred_by ?? 'SELF' }}</span></div>
                <div class="info-item"><span class="info-label">Client Name</span><span class="info-value">: {{ $report->client_name ?? 'N/A' }}</span></div>
            </div>
        </div>

        <!-- Multiple Tests -->
        @foreach($report->report_tests as $test)
            <div class="test-header">
                <h2>{{ strtoupper($test->panel?->name ?? 'HEALTH GUARD GOLD') }}</h2>
                <h3>{{ strtoupper($test->test_name ?? 'LABORATORY TEST') }}</h3>
            </div>

            <table class="results-table">
                <thead>
                    <tr>
                        <th>Test Parameter</th>
                        <th>Result</th>
                        <th>Unit</th>
                        <th>Reference Range</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($test->results as $result)
                        @php
                            $referenceRange = $result->reference_range ?? '';
                            $value = floatval($result->value ?? 0);
                            $status = 'normal';
                            if (preg_match('/(\d+\.?\d*)\s*-\s*(\d+\.?\d*)/', $referenceRange, $matches)) {
                                $min = floatval($matches[1]);
                                $max = floatval($matches[2]);
                                if ($value < $min) { $status = 'low'; }
                                elseif ($value > $max) { $status = 'high'; }
                            }
                        @endphp
                        <tr>
                            <td>{{ $result->parameter_name ?? 'N/A' }}</td>
                            <td><span class="result-{{ $status }}">{{ $result->value ?? '-' }}</span></td>
                            <td>{{ $result->unit ?? '-' }}</td>
                            <td>{{ $result->reference_range ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #64748b; font-style: italic;">No results available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endforeach

        <!-- Remarks -->
        @if(!empty($report->remarks))
        <div class="remarks-section">
            <span class="label">Remarks:</span>
            <span class="value">{{ $report->remarks }}</span>
        </div>
        @endif

        <!-- End of Report -->
        <div class="end-report">
            ------------ END OF REPORT ------------
        </div>
    </div>
</body>
</html>
