<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="pdfkit-page-size" content="A4">
    <meta name="pdfkit-orientation" content="Portrait">

    <title>{{ $report->report_name ?? 'Path24 Labs - Medical Report' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
            font-size: 13px;
            line-height: 1.5;
            color: #000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .results-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

/* Remove all borders */
.results-table th,
.results-table td {
    border: none !important;
    padding: 6px 8px;
}

/* Optional: give header a light underline for separation */
.results-table th {
    background: #fff;
    font-weight: bold;
    border-bottom: 1px solid #ccc;
    text-align: left;
}

/* Group title row (like BLOOD COUNTS) */
.results-table tr td[colspan="4"] {
    background: #f9fafc;
    font-weight: bold;
    padding-top: 10px;
    border: none !important;
}

/* Optional: highlight low/high results */
.results-table td.low { color: blue; font-weight: bold; }
.results-table td.high { color: red; font-weight: bold; }

/* Interpretation section (bottom) */
.interpretation-section {
    margin-top: 20px;
    background: #fafafa;
    padding: 10px 5px;
    border: none;
}

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            /* padding: 25px 30px; */
             padding: 25px 30px 120px 30px;

        }

        /* PDF-friendly header using simple table */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .header-table td {
            vertical-align: top;
            padding: 5px;
        }

        .logo-section img {
            max-width: 220px;
            height: auto;
            display: block;
        }

        /* Simple barcode replacement */
        .barcode {
            width: 160px;
            height: 28px;
            margin-top: 8px;
            border: 2px solid #000;
            background: #fff;
        }

        /* Divider as border */
        .divider-cell {
            width: 3px;
            /* border-left: 3px solid #00838f; */
            padding: 0 !important;
        }

        .contact-info {
            font-size: 12px;
            line-height: 1.7;
            color: #000;
            font-weight: 500;
            border-left: 2px solid;
            padding-left: 12px;
        }

        .contact-info strong {
            color: #00838f;
            font-weight: 700;
        }

        /* .contact-info div {
            margin-bottom: 4px;
        } */

        /* Patient Info - Using simple table instead of grid */
        .patient-info-table {
            width: 100%;
            margin-bottom: 18px;
            font-size: 13px;
            border-collapse: collapse;
        }

        .patient-info-table td {
            padding: 1px 4px;
            vertical-align: top;
        }

        .info-label {
            font-weight: 700;
            width: 150px;
            color: #000;
        }

        /* Add to the existing * selector */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    height: 100%;
}

/* Update body styles */
body {
    font-family: 'Helvetica Neue', Arial, sans-serif;
    font-size: 16px;
    line-height: 1.5;
    color: #000;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Update container styles */
.container {
    /* max-width: 900px; */
    margin: 0 auto;
    background: white;
    /* padding: 25px 30px 30px 30px; */
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* Add new content-wrapper class (wrap all content except footer) */
.content-wrapper {
    flex: 1;
}

/* Update footer styles - REPLACE the existing footer styles */
footer {
    margin-top: auto;
    padding-top: 20px;
}

/* Update @media print section - ADD these lines to existing print styles */
@media print {
    body {
        background: white;
        padding: 0;
        font-size: 13px;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        display: block; /* Override flex for print */
    }

    .container {
        box-shadow: none;
        /* padding: 15px 20px; */
        max-width: 100%;
        display: block; /* Override flex for print */
        padding-bottom: 280px; /* Space for fixed footer */
    }

    footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        padding: 15px 20px 20px 20px;
        margin: 0;
    }

    /* Keep all other existing print styles */
}

        .info-value {
            color: #000;
            font-weight: 600;
        }

        .title {
            text-align: center;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 16px;
            /* margin: 12px 0 8px 0; */
            padding-top: 10px;
            line-height: 18px;
            border-top: 1px solid #333;
            color: #000;
            letter-spacing: 0.5px;
        }

        .section-title {
            text-align: center;
            text-transform: uppercase;
            font-weight: 700;
            font-size: 16px;
            line-height: 18px;
            /* margin: 10px 0 15px 0; */
            padding-bottom: 10px;
            border-bottom: 1px solid #333;
            color: #000;
            letter-spacing: 0.5px;
        }

        /* Test Results Table */
        table.results-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        table.results-table th {
            padding: 6px 10px;
            text-align: left;
            font-weight: 700;
            color: #000;
            font-size: 14px;
        }

        table.results-table td {
            padding: 10px;
            border: 1px solid #ccc;
            color: #000;
            font-weight: 500;
        }

        .category-row {
            font-weight: 700;
            background: #f5f5f5;
        }

        .low {
            color: #0066FF !important;
            font-weight: 700 !important;
            font-size: 14px;
        }

        .high {
            color: #FF0000 !important;
            font-weight: 700 !important;
            font-size: 14px;
        }

        .interpretation-section {
            padding: 12px 8px;
            font-size: 12px;
            line-height: 1.6;
            background: #f9f9f9;
        }

        .interpretation-section strong {
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
            color: #000;
            font-size: 13px;
        }

        .interpretation-section p {
            margin: 5px 0;
            color: #000;
            font-weight: 500;
        }

        .end-report {
            text-align: center;
            margin: 20px 0;
            font-weight: 700;
            font-size: 13px;
            color: #000;
            letter-spacing: 1px;
        }

        /* Footer - Using table for better PDF support */
        .footer-table {
            width: 100%;
            margin-top: 25px;
            padding-top: 20px;
            /* border-top: 2px solid #ddd; */
            border-collapse: collapse;
        }

        .footer-table td {
            vertical-align: bottom;
        }

        .qr-code {
            width: 90px;
            height: 90px;
            border: 2px solid #000;
            background: #fff;
        }

        .qr-label {
            text-align: left;
            font-size: 11px;
            margin-top: 5px;
            font-weight: 700;
            color: #000;
        }

        .signature-section {
            text-align: right;
        }

        .signature-section img {
            max-width: 160px;
            height: auto;
            margin-bottom: 5px;
        }

        .doctor-name {
            font-weight: 700;
            font-size: 14px;
            margin-top: 5px;
            color: #000;
        }

        .designation {
            font-size: 12px;
            color: #333;
            font-weight: 600;
        }

        .note-footer {
            background: #00838f;
            color: white;
            padding: 14px 12px;
            margin-top: 20px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 4px;
        }

        .note-footer strong {
            font-weight: 700;
        }
        footer{
                /* position: absolute; */
                bottom: 0;
                max-width: 100%;
                box-sizing: border-box;
                width: 840px;
                position: relative;

        }

        /* PDF-specific styles */
        @page {
            /* margin: 12mm; */
            size: A4;
            /* margin: 12mm 12mm 20mm 12mm; */
        }

        @media print {
            body {
                background: white;
                padding: 0;
                font-size: 13px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .container {
                box-shadow: none;
                padding: 15px 20px;
                max-width: 100%;
            }

            /* Enhanced font rendering for PDF */
            * {
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                text-rendering: optimizeLegibility;
            }

            /* Make fonts bolder in PDF */
            .header-table,
            .patient-info-table,
            .results-table,
            .footer-table {
                font-weight: 600;
            }

            .info-label,
            .info-value strong,
            table.results-table th,
            .doctor-name,
            .title,
            .section-title {
                font-weight: 700 !important;
            }

            /* Ensure colors print */
            .low {
                color: #0066FF !important;
            }

            .high {
                color: #FF0000 !important;
            }

            .note-footer {
                background: #00838f !important;
                color: white !important;
            }

            /* Better border visibility in PDF */
            .header-table {
                /* border-bottom: 3px solid #000; */
            }

            table.results-table th {
                border: 2px solid #666 !important;
                background: #e0e0e0 !important;
            }

            table.results-table td {
                border: 1px solid #999 !important;
            }

            /* Remove page breaks inside tables */
            table.results-table {
                page-break-inside: avoid;
            }


            table.results-table tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td style="width: 40%;">
                    <div class="logo-section">
                        @php
                            $logoPath = public_path('storage/' . auth()->user()->logo);
                            $logoBase64 = '';
                            if (file_exists($logoPath)) {
                                $imageData = base64_encode(file_get_contents($logoPath));
                                $imageType = pathinfo($logoPath, PATHINFO_EXTENSION);
                                $logoBase64 = 'data:image/' . $imageType . ';base64,' . $imageData;
                            }
                        @endphp
                        @if($logoBase64)
                            <img src="{{ $logoBase64 }}" alt="Logo">
                        @endif
                        <div class="barcode"></div>
                    </div>
                </td>
                <td class="divider-cell"></td>
                <td style="width: 45%;">
                    <div class="contact-info">
                        <div><strong>Main Lab:</strong> {{ auth()->user()?->address ?? '...' }}</div>
                        <div><strong>Customer Care:</strong> {{ auth()->user()?->mobile ?? '...' }}</div>
                        <div><strong>Reference Lab:</strong> {{ auth()->user()?->reference_lab ?? '...' }}</div>
                        <div>In Front of M.Y. Hospital, Gate No. 2, Indore (M.P.)</div>
                        <div><strong>Email:</strong> {{ auth()->user()?->email ?? '...' }}</div>
                        <div><strong>Web:</strong> {{ auth()->user()?->website ?? '...' }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Patient Info -->
        <table class="patient-info-table">
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr>
                            <td class="info-label">Lab Code</td>
                            <td class="info-value">: <strong>{{ auth()->user()->lab_code ?? '-' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="info-label">Patient Name</td>
                            <td class="info-value">: <strong>{{ strtoupper($report->patient_name ?? '-') }}</strong></td>
                        </tr>
                        <tr>
                            <td class="info-label">Age</td>
                            <td class="info-value">: {{ $report->age ?? '-' }} / {{ strtoupper($report->gender ?? '-') }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Referred By</td>
                            <td class="info-value">: <strong>{{ strtoupper($report->referred_by ?? 'SELF') }}</strong></td>
                        </tr>
                        <tr>
                            <td class="info-label">Client Name</td>
                            <td class="info-value">: <strong>{{ strtoupper($report->client_name ?? '-') }}</strong></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%; text-align: right;">
                    <table style="width: 100%;">
                        <tr>
                            <td class="info-label" style="text-align: right;">Sam.Registered At</td>
                            <td class="info-value" style="text-align: right;">: {{ \Carbon\Carbon::parse($report->collection_date ?? '')->format('d/m/Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="info-label" style="text-align: right;">Reported At</td>
                            <td class="info-value" style="text-align: right;">: {{ \Carbon\Carbon::parse($report->reporting_date ?? '')->format('d/m/Y h:i A') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Lab Name Title -->
        <div class="title">
            {{ optional(\App\Models\Lab::find(auth()->user()?->lab_id))->name ?? 'Authorized Signatory' }}
        </div>

        <!-- Section Title -->
        @if ($report->results->isNotEmpty())
            @php
                $firstResult = $report->results->first();
                $categoryName = optional(optional($firstResult)->test->category)->name ?? ($firstResult->category_name ?? null);
                $panelName = optional($report->panel)->name;
                $testName = optional(optional($firstResult)->test)->name;
            @endphp

            <div class="section-title">
                @if (!empty($panelName))
                    {{ $panelName }}
                @elseif(!empty($testName))
                    {{ $testName }}
                @elseif(!empty($categoryName))
                    {{ $categoryName }}
                @else
                    Uncategorized
                @endif
            </div>
        @endif

        <!-- Test Results Table -->
    <!-- Test Results Table (simple list, drag order) -->
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
        @php
            $interpretations = collect();
        @endphp

        @foreach ($report->results as $result) {{-- ✅ NO sortBy, respects display_order --}}
            @php
                $value      = floatval($result->value);
                $range      = trim($result->reference_range ?? '');
                $colorClass = '';

                if ($range && is_numeric($value)) {
                    // try to parse "min - max"
                    if (preg_match_all('/\d+(?:\.\d+)?/', $range, $matches) && count($matches[0]) >= 2) {
                        $min = floatval($matches[0][0]);
                        $max = floatval($matches[0][1]);
                        if ($value < $min) $colorClass = 'low';
                        elseif ($value > $max) $colorClass = 'high';

                    // parse "> x"
                    } elseif (preg_match('/>\s*(\d+(?:\.\d+)?)/', $range, $m)) {
                        if ($value <= floatval($m[1])) $colorClass = 'low';

                    // parse "< x"
                    } elseif (preg_match('/<\s*(\d+(?:\.\d+)?)/', $range, $m)) {
                        if ($value >= floatval($m[1])) $colorClass = 'high';
                    }
                }

                // collect interpretation/comment (optional)
                $groupName = optional($result->test)->test_group;
                if ($groupName) {
                    $groupTest = \App\Models\Test::where('test_group', $groupName)->first();
                    if ($groupTest && (!empty($groupTest->interpretation) || !empty($groupTest->comment))) {
                        $interpretations->push($groupTest);
                    }
                }
            @endphp

            <tr>
                <td>{{ $result->test_name }}</td>
                <td class="{{ $colorClass }}">
                    <strong>{{ $result->value ?? '-' }}</strong>
                </td>
                <td>{{ $result->unit ?? '-' }}</td>
                <td>{{ $result->reference_range ?? '-' }}</td>
            </tr>
        @endforeach

        {{-- Optional: show interpretations/comments once at bottom --}}
        @if ($interpretations->isNotEmpty())
            <tr>
                <td colspan="4">
                    <div class="interpretation-section">
                        @foreach ($interpretations->unique('test_group') as $groupTest)
                            @if (!empty($groupTest->interpretation))
                                <strong>{{ strtoupper($groupTest->test_group) }} - Interpretation:</strong>
                                <p>{{ $groupTest->interpretation }}</p>
                            @endif

                            @if (!empty($groupTest->comment))
                                <strong>{{ strtoupper($groupTest->test_group) }} - Comment:</strong>
                                <p style="text-decoration: none;font-size:16px;">
                                    {{ $groupTest->comment }}
                                </p>
                            @endif
                        @endforeach
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
</table>



        <div class="end-report">------------- END OF REPORT ---------------</div>

        <!-- Footer -->
     <footer>
  <table class="footer-table">
    <tr>
      <td style="width: 30%;">
        <div class="qr-code"></div>
        <div class="qr-label">Scan to Validate</div>
      </td>
      <td style="width: 70%;">
        <div class="signature-section">
          @if (auth()->user()->digital_signature)
              @php
                  $signaturePath = public_path('storage/' . auth()->user()->digital_signature);
                  $signatureBase64 = '';
                  if (file_exists($signaturePath)) {
                      $imageData = base64_encode(file_get_contents($signaturePath));
                      $imageType = pathinfo($signaturePath, PATHINFO_EXTENSION);
                      $signatureBase64 = 'data:image/' . $imageType . ';base64,' . $imageData;
                  }
              @endphp
              @if($signatureBase64)
                  <img src="{{ $signatureBase64 }}" alt="Signature">
              @endif
          @endif
          <div class="doctor-name">{{ auth()->user()?->name ?? 'Authorized Signatory' }}</div>
          <div class="designation">{{ auth()->user()?->qualification ?? 'Medical Director' }}</div>
        </div>
      </td>
    </tr>
  </table>

  @if (auth()->user()?->note)
      <div class="note-footer" style="font-size: 14px;">
          <strong>Note:</strong> {{ auth()->user()->note }}
      </div>
  @endif
</footer>

    </div>
</body>
</html>
