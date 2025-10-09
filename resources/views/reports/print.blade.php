<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lab Report - {{ $report->patient_name ?? 'N/A' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            background: #fff;
            color: #333;
            line-height: 1.5;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            border: 2px solid #000;
            background: #fff;
        }

        /* HEADER */
        header {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            border-bottom: 2px solid #000;
            align-items: flex-start;
        }
        .logo-section { flex: 1; }
        .logo-text {
            font-size: 42px;
            font-weight: bold;
            color: #008B8B;
            line-height: 1;
        }
        .logo-text .path { color: #008B8B; }
        .logo-text .number { color: #FDB714; }
        .tagline {
            color: #FDB714;
            font-size: 14px;
            font-style: italic;
            margin-top: 5px;
        }
        .lab-name {
            color: #008B8B;
            font-weight: bold;
            font-size: 18px;
            margin-top: 4px;
        }
        .lab-details {
            text-align: right;
            font-size: 11px;
            line-height: 1.6;
        }
        .lab-details strong { font-weight: bold; }

        /* PATIENT INFO */
        .patient-info {
            padding: 15px 20px;
            border-bottom: 2px solid #000;
            background: #f9f9f9;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px 20px;
            font-size: 13px;
        }
        .info-label { font-weight: bold; color: #000; }
        .info-value { color: #333; }

        /* TEST HEADER */
        .test-header {
            background: #e9f7f7;
            padding: 10px 20px;
            text-align: center;
            border-bottom: 2px solid #000;
        }
        .test-header h2 {
            font-size: 16px;
            font-weight: bold;
            color: #008B8B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* RESULTS TABLE */
        .results-section {
            padding: 20px;
        }
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .results-table thead {
            background: #f1f1f1;
        }
        .results-table th,
        .results-table td {
            padding: 10px 12px;
            font-size: 13px;
            border: 1px solid #ccc;
            text-align: left;
        }
        .results-table th {
            font-weight: bold;
            color: #000;
        }
        .results-table td {
            color: #333;
        }
        .results-table tbody tr:hover {
            background: #f9f9f9;
        }

        /* Result Status Colors */
        .result-low { color: #d32f2f; font-weight: bold; }
        .result-high { color: #c2185b; font-weight: bold; }
        .result-normal { color: #388e3c; font-weight: bold; }

        /* REMARKS */
        .remarks-section {
            padding: 15px 20px;
            border-top: 2px solid #ddd;
            font-size: 13px;
            background: #fcfcfc;
        }
        .remarks-section .label {
            font-weight: bold;
            margin-right: 10px;
        }

        /* FOOTER */
        .end-report {
            text-align: center;
            padding: 15px;
            font-size: 12px;
            font-weight: bold;
            border-top: 2px solid #000;
            letter-spacing: 1px;
        }

        /* PRINT SETTINGS */
        @media print {
            body { padding: 0; margin: 0; }
            .container { border: none; }
            @page { margin: 8mm; }
            header { page-break-inside: avoid; }
            .test-header { page-break-after: avoid; }
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- HEADER -->
        <header>
            <div class="logo-section">
                <div class="logo-text">
                    <span class="path">Zalgo</span><span class="number">24</span>
                </div>
                <div class="tagline">Your health guard</div>
                <div class="lab-name">Labs</div>
            </div>
            <div class="lab-details">
                <div><strong>Main Lab:</strong> 14, Geeta Colony, Near Natyakala Mandir</div>
                <div>Hospital Road, Lashkar, Gwalior - 474009 (M.P.)</div>
                <div><strong>Customer Care:</strong> 91119-02424, 0751-4712424</div>
                <div><strong>Reference Lab:</strong> 31, Jaora Compound, In Front of M.Y. Hospital, Indore (M.P.)</div>
                <div><strong>Email:</strong> path24labsgwl@gmail.com</div>
                <div><strong>Web:</strong> www.path24labs.com</div>
            </div>
        </header>

        <!-- PATIENT INFO -->
        <div class="patient-info">
            <div class="info-grid">
                <div><span class="info-label">Patient Name:</span> <span class="info-value">{{ $report->patient_name ?? 'N/A' }}</span></div>
                <div><span class="info-label">Registered At:</span> <span class="info-value">{{ $report->created_at ? \Carbon\Carbon::parse($report->created_at)->format('d/m/Y h:i A') : '-' }}</span></div>
                <div><span class="info-label">Age/Gender:</span> <span class="info-value">{{ $report->age ?? '-' }}Y / {{ strtoupper($report->gender ?? '-') }}</span></div>
                <div><span class="info-label">Reported At:</span> <span class="info-value">{{ \Carbon\Carbon::parse($report->test_date)->format('d/m/Y h:i A') ?? '-' }}</span></div>
                <div><span class="info-label">Referred By:</span> <span class="info-value">{{ $report->referred_by ?? 'SELF' }}</span></div>
                <div><span class="info-label">Client Name:</span> <span class="info-value">{{ $report->client_name ?? 'N/A' }}</span></div>
            </div>
        </div>

        <!-- TEST HEADER -->
       <div class="test-header">
    <h2>LABORATORY TEST RESULTS - {{ $report->panel->name ?? 'N/A' }}</h2>
</div>


        <!-- TEST RESULTS TABLE -->
        <div class="results-section">
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Test Name</th>
                        {{-- <th>Parameter</th> --}}
                        <th>Value</th>
                        <th>Unit</th>
                        <th>Reference Range</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report->results ?? [] as $result)
                    @php
                        $resultClass = '';
                        if ($result->reference_range && $result->value) {
                            $range = explode('-', $result->reference_range);
                            if (count($range) == 2) {
                                $val = floatval(str_replace(',', '', $result->value));
                                $min = floatval(trim($range[0]));
                                $max = floatval(trim($range[1]));

                                if ($val < $min) {
                                    $resultClass = 'result-low';
                                } elseif ($val > $max) {
                                    $resultClass = 'result-high';
                                } else {
                                    $resultClass = 'result-normal';
                                }
                            }
                        }
                    @endphp
                    <tr>
                        <td>{{ $result->test_name ?? '-' }}</td>
                        {{-- <td>{{ $result->parameter_name ?? '-' }}</td> --}}
                        <td class="{{ $resultClass }}">{{ $result->value ?? '-' }}</td>
                        <td>{{ $result->unit ?? '-' }}</td>
                        <td>{{ $result->reference_range ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">No test results found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- REMARKS -->
        @if(!empty($report->remarks))
            <div class="remarks-section">
                <span class="label">Remarks:</span>
                <span class="value">{{ $report->remarks }}</span>
            </div>
        @endif

        <!-- END -->
        <div class="end-report">
            ------------ END OF REPORT ------------
        </div>
    </div>
</body>
</html>
