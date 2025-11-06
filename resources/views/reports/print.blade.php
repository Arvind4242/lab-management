<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->report_name ?? 'Path24 Labs - Medical Report' }}</title>
    <style>
        /* ✅ Your original CSS is untouched */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        /* .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 2px solid #333; } */
      /* ---------- HEADER SECTION ---------- */
.header {
    display: flex;
    align-items: stretch;
    justify-content: space-between; /* 👈 pushes right block to far right */
    padding: 10px 20px;
    font-family: Arial, sans-serif;
    border-bottom: 2px solid #ddd;
    margin-bottom: 10px;
}

.logo-section {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-start;
    gap: 8px;
}

.logo-section img {
    width: 180px;
}

.barcode {
    width: 150px;
    height: 25px;
    margin-top: 10px;
    background: repeating-linear-gradient(
        to right,
        black 0,
        black 2px,
        white 2px,
        white 4px
    );
}

/* 👇 Wraps divider + contact info, aligned to the right */
.right-block {
    display: flex;
    align-items: stretch;
    justify-content: flex-end;
}

/* Divider: full height of contact section */
.divider {
    width: 2px;
    background-color: #00838f;
    margin: 8px 20px 8px 0;
    border-radius: 2px;
}

/* Contact Info Right-Aligned */
.contact-info {
    text-align: left;        /* 👈 aligns all text to the right */
    font-size: 13px;
    line-height: 1.6;
    color: #333;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.contact-info strong {
    color: #00838f;
}




        /* .logo-section { flex: 1; } */
        .logo { font-size: 42px; font-weight: bold; margin-bottom: 5px; }
        .path { color: #008B8B; }
        .low {color: blue; font-weight: 600;}
        .high {color: red; font-weight: 600;}
        .number { color: #FFD700; }
        .labs { color: #008B8B; }
        .tagline { color: #FFD700; font-size: 14px; font-style: italic; margin-bottom: 10px; }
        /* .barcode { width: 150px; height: 30px; background: repeating-linear-gradient(90deg, #000 0px, #000 2px, #fff 2px, #fff 4px); } */
        /* .contact-info { text-align: right; font-size: 11px; line-height: 1.6; }
        .contact-info strong { font-weight: bold; } */
        .patient-info { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px; font-size: 13px; }
        .info-row { display: flex; padding: 5px 0; }
        .info-label { font-weight: bold; width: 140px; }
        .info-value { flex: 1; }
        .right-align { text-align: right; }
        .report-title { text-align: center; font-weight: bold; font-size: 16px; margin: 20px 0 10px 0; margin-top: 10px; padding-top: 10px; border-top: 2px solid #333; }
        .section-title { text-align: center; font-weight: bold; font-size: 16px; margin: 7px 0; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 2px solid #333; }
        .title { text-align: center; font-weight: bold; font-size: 16px; margin: 7px 0; margin-top: 10px; padding-top: 10px; border-top: 2px solid #333; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 12px; }
        th { background: #f0f0f0; padding: 10px; text-align: left; font-weight: bold; border: 1px solid #ddd; }
        td { padding: 8px 10px; border: 1px solid #ddd; }
        .category-row { font-weight: bold; background: #f9f9f9; }
        .abnormal { color: #d32f2f; font-weight: bold; }
        .footer { margin-top: 30px; display: flex; justify-content: space-between; align-items: flex-end; padding-top: 20px; }
        .qr-code { width: 80px; height: 80px; background: #000; position: relative; }
        .qr-code::after { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 60px; height: 60px;
            background: repeating-linear-gradient(0deg, #fff 0px, #fff 3px, #000 3px, #000 6px),
                        repeating-linear-gradient(90deg, #fff 0px, #fff 3px, #000 3px, #000 6px); }
        .qr-label { text-align: center; font-size: 11px; margin-top: 5px; font-weight: bold; }
        .signature-section { text-align: right; }
        .signature { font-family: 'Brush Script MT', cursive; font-size: 24px; margin-bottom: 5px; }
        .doctor-name { font-weight: bold; font-size: 13px; }
        .designation { font-size: 12px; color: #666; }
        .note-footer { background: #008B8B; color: white; padding: 10px 15px; margin-top: 20px; font-size: 11px; font-style: italic; }
        .end-report { text-align: center; margin: 20px 0; font-weight: bold; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
       <div class="header">
    <div class="logo-section">
        <div class="logo">
            <img style="width: 200px" src="{{ asset('storage/' . auth()->user()->logo) }}" alt="">
        </div>
        <div class="barcode"></div>
    </div>

    <div class="right-block">
        <div class="divider"></div>

        <div class="contact-info">
            <div><strong>Main Lab:</strong> {{ auth()->user()?->address ?? 'shubhas puri ghasmandi, kilagate, gwalior' }}</div>
            <div><strong>Customer Care:</strong> {{ auth()->user()?->mobile ?? '7974056842' }}</div>
            <div><strong>Reference Lab:</strong> {{ auth()->user()?->reference_lab ?? '31, Jaora Compound' }}</div>
            <div>In Front of M.Y. Hospital, Gate No. 2, Indore (M.P.)</div>
            <div><strong>Email:</strong> {{ auth()->user()?->email ?? 'info@lab.com' }}</div>
            <div><strong>Web:</strong> {{ auth()->user()?->website ?? 'www.lab.com' }}</div>
        </div>
    </div>
</div>



        <!-- Patient Info -->
        <div class="patient-info">
            <div>
                <div class="info-row"><span class="info-label">Lab Code</span><span class="info-value">: <strong>{{ auth()->user()->lab_code ?? '-' }}</strong></span></div>
                <div class="info-row"><span class="info-label">Patient Name</span><span class="info-value">: <strong>{{ strtoupper($report->patient_name ?? '-') }}</strong></span></div>
                <div class="info-row"><span class="info-label">Age</span><span class="info-value">: <strong>{{ $report->age ?? '-' }}</strong> / {{ strtoupper($report->gender ?? '-') }}</span></div>
                <div class="info-row"><span class="info-label">Referred By</span><span class="info-value">: <strong>{{ strtoupper($report->referred_by ?? 'SELF') }}</strong></span></div>
                <div class="info-row"><span class="info-label">Client Name</span><span class="info-value">: <strong>{{ strtoupper($report->client_name ?? '-') }}</strong></span></div>
            </div>
            <div class="right-align">
                <div class="info-row"><span class="info-label">Sam.Registered At</span><span class="info-value">: {{ \Carbon\Carbon::parse($report->collection_date ?? '')->format('d/m/Y h:i A') }}</span></div>
                <div class="info-row"><span class="info-label">Reported At</span><span class="info-value">: {{ \Carbon\Carbon::parse($report->reporting_date ?? '')->format('d/m/Y h:i A') }}</span></div>
            </div>
        </div>
        <!-- Report Title -->

<div class="title">
{{ optional(\App\Models\Lab::find(auth()->user()?->lab_id))->name ?? 'Authorized Signatory' }}

</div>

    {{-- <div class="report-title">{{ strtoupper($report->report_name ?? '-') }}</div> --}}

    {{-- <div class="section-title">{{ $result->category_name }}</div> --}}
     @if($report->results->isNotEmpty())
@php
    $firstResult = $report->results->first();

    // Safely extract values using optional() to avoid errors
    $categoryName = optional(optional($firstResult)->test->category)->name
        ?? $firstResult->category_name
        ?? null;

    $panelName = optional($report->panel)->name;
    $testName = optional(optional($firstResult)->test)->name;
@endphp

<div class="section-title">
    @if(!empty($panelName))
        {{-- 🩺 If Panel exists, show only Panel (highest priority) --}}
        {{ $panelName }}
    @elseif(!empty($testName))
        {{-- 🧪 If no panel but Test exists, show the Test --}}
        {{ $testName }}
    @elseif(!empty($categoryName))
        {{-- 📂 If only Category exists, show the Category --}}
        {{ $categoryName }}
    @else
        {{-- (Optional) Default fallback --}}
        Uncategorized
    @endif
</div>


@endif



        <!-- Dynamic Test Table -->
  <table>
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
    $currentCategory = null;
@endphp

@foreach($report->results as $result)
    @php
        $categoryName = $result->test->category->name ?? $result->category_name ?? 'Uncategorized';
    @endphp

    {{-- ✅ When category changes, show its title --}}
    @if($categoryName !== $currentCategory)
        @if($currentCategory !== null)
            {{-- ✅ Show Interpretation & Comment for previous category --}}
            @php
                $categoryTest = \App\Models\Test::whereHas('category', function($q) use ($currentCategory) {
                    $q->where('name', $currentCategory);
                })->first();
            @endphp

            @if(!empty($categoryTest?->interpretation) || !empty($categoryTest?->comment))
                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <div class="interpretation-section">
                            @if(!empty($categoryTest?->interpretation))
                                <strong>Interpretation :</strong>
                                <p>{{ $categoryTest->interpretation }}</p>
                            @endif

                            @if(!empty($categoryTest?->comment))
                                <strong>Comment :</strong>
                                <p>{{ $categoryTest->comment }}</p>
                            @endif
                        </div>
                    </td>
                </tr>
            @endif
        @endif

        {{-- Start new category --}}
        {{-- <tr class="category-row">
            <td colspan="4">{{ strtoupper($categoryName) }}</td>
        </tr> --}}

        @php $currentCategory = $categoryName; @endphp
    @endif

    {{-- Normal test row --}}
    @php
        $value = floatval($result->value);
        $range = trim($result->reference_range);
        $color = 'black';

        if ($range && is_numeric($value)) {
            if (preg_match_all('/\d+(?:\.\d+)?/', $range, $matches) && count($matches[0]) >= 2) {
                $min = floatval($matches[0][0]);
                $max = floatval($matches[0][1]);
                if ($value < $min) $color = 'blue';
                elseif ($value > $max) $color = 'red';
            } elseif (preg_match('/>\s*(\d+(?:\.\d+)?)/', $range, $m)) {
                if ($value <= floatval($m[1])) $color = 'blue';
            } elseif (preg_match('/<\s*(\d+(?:\.\d+)?)/', $range, $m)) {
                if ($value >= floatval($m[1])) $color = 'red';
            }
        }
    @endphp

    <tr>
        <td>{{ $result->test_name }}</td>
        <td style="color: {{ $color }}">{{ $result->value ?? '-' }}</td>
        <td>{{ $result->unit ?? '-' }}</td>
        <td>{{ $result->reference_range ?? '-' }}</td>
    </tr>
@endforeach

{{-- ✅ After loop ends, show last category interpretation --}}
@if($currentCategory !== null)
    @php
        $categoryTest = \App\Models\Test::whereHas('category', function($q) use ($currentCategory) {
            $q->where('name', $currentCategory);
        })->first();
    @endphp

    @if(!empty($categoryTest?->interpretation) || !empty($categoryTest?->comment))
        <tr>
            <td colspan="4" style="padding-top:10px;">
                <div class="interpretation-section">
                    @if(!empty($categoryTest?->interpretation))
                        <strong>Interpretation :</strong>
                        <p>{{ $categoryTest->interpretation }}</p>
                    @endif

                    @if(!empty($categoryTest?->comment))
                        <strong>Comment :</strong>
                        <p>{{ $categoryTest->comment }}</p>
                    @endif
                </div>
            </td>
        </tr>
    @endif
@endif

    </tbody>
</table>




        <div class="end-report">------------- END OF REPORT ---------------</div>

        <!-- Footer -->
        <div class="footer">
            <div>
                <div class="qr-code"></div>
                <div class="qr-label">Scan to Validate</div>
            </div>
            <div class="signature-section">
                @if(auth()->user()->digital_signature)
    <div class="signature" style="margin-top: 20px;">
        <img
            src="{{ asset('storage/' . auth()->user()->digital_signature) }}"
            alt="Signature"
            style="width: 150px; height: auto;"
        >
    </div>
@else
    <p><em>No signature available</em></p>
@endif
{{-- {{ asset('storage/' . auth()->user()->logo) }} --}}
                <div class="doctor-name">{{ auth()->user()?->name ?? 'Authorized Signatory' }}</div>
                <div class="designation"> {{ auth()->user()?->qualification ?? 'Medical Director' }}</div>
            </div>
        </div>

        <div class="note-footer">
            @if(auth()->user()?->address)
                    <strong>Note:</strong> {{ auth()->user()->note }}
                @endif
        </div>

    </div>
</body>
</html>
