<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Report - {{ $report->patient_name ?? 'N/A' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border: 2px solid #333;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 25px 30px;
            border-bottom: 3px solid #008B8B;
            background: linear-gradient(to bottom, #fff 0%, #f9f9f9 100%);
        }

        .header-left {
            flex: 1;
        }

        .logo-img {
            height: 60px;
            width: auto;
            margin-bottom: 10px;
        }

        .lab-name {
            font-size: 32px;
            font-weight: bold;
            color: #008B8B;
            margin-bottom: 5px;
        }

        .lab-code {
            color: #FDB714;
            font-size: 28px;
            font-weight: bold;
        }

        .tagline {
            color: #FDB714;
            font-size: 13px;
            font-style: italic;
            margin: 5px 0;
        }

        .qualification {
            color: #008B8B;
            font-weight: bold;
            font-size: 16px;
        }

        .header-right {
            text-align: right;
            font-size: 11px;
            line-height: 1.8;
            color: #333;
        }

        .header-right div {
            margin-bottom: 3px;
        }

        .header-right strong {
            font-weight: 600;
            color: #000;
        }

        /* Patient Info Section */
        .patient-info {
            padding: 20px 30px;
            background: #f8f8f8;
            border-bottom: 2px solid #ddd;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px 30px;
            font-size: 13px;
        }

        .info-item {
            display: flex;
        }

        .info-label {
            font-weight: bold;
            color: #000;
            min-width: 120px;
        }

        .info-value {
            color: #333;
        }

        /* Test Header */
        .test-header {
            background: #008B8B;
            color: white;
            text-align: center;
            padding: 12px;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Results Section */
        .results-section {
            padding: 25px 30px;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .results-table thead {
            background: #f0f0f0;
        }

        .results-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 13px;
            border: 1px solid #ddd;
            color: #000;
        }

        .results-table td {
            padding: 12px;
            border: 1px solid #ddd;
            font-size: 13px;
            color: #333;
        }

        .results-table tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .results-table tbody tr:hover {
            background: #f5f5f5;
        }

        /* Result Status Colors */
        .result-low {
            color: #d32f2f;
            font-weight: bold;
        }

        .result-high {
            color: #c2185b;
            font-weight: bold;
        }

        .result-normal {
            color: #388e3c;
            font-weight: bold;
        }

        /* Remarks Section */
        .remarks-section {
            padding: 20px 30px;
            background: #fffbea;
            border-top: 2px solid #FDB714;
            border-bottom: 2px solid #ddd;
            font-size: 12px;
            line-height: 1.7;
        }

        .remarks-label {
            font-weight: bold;
            color: #000;
            margin-bottom: 8px;
            display: block;
        }

        .remarks-text {
            color: #555;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            border-top: 3px solid #008B8B;
            background: #f9f9f9;
        }

        .end-text {
            font-weight: bold;
            font-size: 13px;
            letter-spacing: 2px;
            color: #333;
        }

        .footer-note {
            margin-top: 10px;
            font-size: 11px;
            color: #666;
            font-style: italic;
        }

        /* QR Code / Signature Section */
        .signature-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding: 20px 30px;
            border-top: 1px solid #ddd;
        }

        .qr-code {
            text-align: left;
        }

        .qr-code img {
            height: 80px;
            width: 80px;
        }

        .signature {
            text-align: right;
        }

        .signature-line {
            border-top: 2px solid #333;
            width: 200px;
            margin-bottom: 5px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 13px;
        }

        .signature-title {
            font-size: 11px;
            color: #666;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .container {
                border: 1px solid #333;
            }

            .header {
                flex-direction: column;
                padding: 20px;
            }

            .header-left {
                margin-bottom: 15px;
            }

            .header-right {
                text-align: left;
            }

            .lab-name {
                font-size: 24px;
            }

            .lab-code {
                font-size: 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .results-section,
            .patient-info,
            .remarks-section {
                padding: 15px;
            }

            .results-table {
                font-size: 11px;
            }

            .results-table th,
            .results-table td {
                padding: 8px;
            }

            .signature-section {
                flex-direction: column;
                gap: 20px;
                align-items: center;
            }

            .signature {
                text-align: center;
            }
        }

        /* Print Styles */
        @media print {
            body {
                padding: 0;
                background: white;
            }

            .container {
                border: none;
                box-shadow: none;
            }

            @page {
                margin: 10mm;
            }

            .header,
            .test-header,
            .results-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- HEADER -->
        <div class="header">
            <div class="header-left">
                {{-- Logo --}}
                @if(auth()->user()?->logo)
                    <img src="{{ asset('storage/' . auth()->user()->logo) }}"
                         alt="Lab Logo"
                         class="logo-img">
                @endif

                {{-- Lab Name and Code --}}
                {{-- <div>
                    <span class="lab-name">{{ auth()->user()->name ?? 'Lab Name' }}</span>
                    @if(auth()->user()?->lab_code)
                        <span class="lab-code">{{ auth()->user()->lab_code }}</span>
                    @endif
                </div> --}}

                {{-- Tagline --}}
                {{-- <div class="tagline">
                    {{ auth()->user()->note ?? 'Smart Health Smart Living' }}
                </div> --}}

                {{-- Qualification --}}
                {{-- @if(auth()->user()?->qualification)
                    <div class="qualification">
                        {{ auth()->user()->qualification }} Labs
                    </div>
                @endif --}}
            </div>

            <div class="header-right">
                {{-- Main Lab Address --}}
                @if(auth()->user()?->address)
                    <div><strong>Main Lab:</strong> {{ auth()->user()->address }}</div>
                @endif

                {{-- Mobile / Contact --}}
                @if(auth()->user()?->mobile)
                    <div><strong>Customer Care:</strong> {{ auth()->user()->mobile }}</div>
                @endif

                {{-- Reference Lab --}}
                @if(auth()->user()?->reference_lab)
                    <div><strong>Reference Lab:</strong> {{ auth()->user()->reference_lab }}</div>
                @endif

                {{-- Email --}}
                <div><strong>Email:</strong> {{ auth()->user()?->email ?? 'info@lab.com' }}</div>

                {{-- Website --}}
                @if(auth()->user()?->website)
                    <div><strong>Web:</strong> {{ auth()->user()->website }}</div>
                @endif
            </div>
        </div>

        <!-- PATIENT INFORMATION -->
        <div class="patient-info">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Patient Name:</span>
                    <span class="info-value">{{ $report->patient_name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Registered At:</span>
                    <span class="info-value">
                        {{ $report->created_at ? \Carbon\Carbon::parse($report->created_at)->format('d/m/Y h:i A') : '-' }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Age/Gender:</span>
                    <span class="info-value">{{ $report->age ?? '-' }}Y / {{ strtoupper($report->gender ?? '-') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Reported At:</span>
                    <span class="info-value">
                        {{ $report->test_date ? \Carbon\Carbon::parse($report->test_date)->format('d/m/Y h:i A') : '-' }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Referred By:</span>
                    <span class="info-value">{{ $report->referred_by ?? 'SELF' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Client Name:</span>
                    <span class="info-value">{{ $report->client_name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- TEST HEADER -->
        <div class="test-header">
            LABORATORY TEST RESULTS - {{ strtoupper($report->panel->name ?? 'N/A') }}
        </div>

        <!-- TEST RESULTS TABLE -->
        <div class="results-section">
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Test Name</th>
                        <th>Result</th>
                        <th>Unit</th>
                        <th>Biological Reference Interval</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report->results ?? [] as $result)
                        @php
                            $resultClass = '';
                            if ($result->reference_range && $result->value && $result->value !== '-') {
                                // Check if reference range contains comparison operators
                                if (strpos($result->reference_range, '<') !== false ||
                                    strpos($result->reference_range, '>') !== false) {
                                    // Handle ranges like "<20 mm/hr" or ">50"
                                    $val = floatval(preg_replace('/[^0-9.]/', '', $result->value));
                                    if (strpos($result->reference_range, '<') !== false) {
                                        $threshold = floatval(preg_replace('/[^0-9.]/', '', $result->reference_range));
                                        if ($val >= $threshold) {
                                            $resultClass = 'result-high';
                                        } else {
                                            $resultClass = 'result-normal';
                                        }
                                    }
                                } elseif (strpos($result->reference_range, '-') !== false) {
                                    // Handle ranges like "12-15" or "4800-10800"
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
                            }
                        @endphp
                        <tr>
                            <td>{{ $result->test_name ?? '-' }}</td>
                            <td class="{{ $resultClass }}">{{ $result->value ?? '-' }}</td>
                            <td>{{ $result->unit ?? '-' }}</td>
                            <td>{{ $result->reference_range ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 20px;">
                                No test results found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- REMARKS SECTION -->
        @if(!empty($report->remarks))
            <div class="remarks-section">
                <span class="remarks-label">Remarks / Important Information:</span>
                <div class="remarks-text">{{ $report->remarks }}</div>
            </div>
        @endif

        <!-- SIGNATURE & QR CODE -->
        <div class="signature-section">
            <div class="qr-code">
                {{-- You can add QR code here if needed --}}
                {{-- <img src="{{ $qrCode }}" alt="QR Code"> --}}
                <div style="font-size: 11px; color: #666;">Report ID: {{ $report->id ?? 'N/A' }}</div>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-name">
                    {{ auth()->user()?->name ?? 'Authorized Signatory' }}
                </div>
                <div class="signature-title">
                    {{ auth()->user()?->qualification ?? 'Medical Director' }}
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="end-text">------------ END OF REPORT ------------</div>
            <div class="footer-note">
                 @if(auth()->user()?->address)
                    <div><strong>Note:</strong> {{ auth()->user()->note }}</div>
                @endif
                {{-- Note: This report is subject to the terms and conditions mentioned overleaf.
                Partial reproduction of this report is not permitted. --}}
            </div>
        </div>

    </div>
</body>
</html>
