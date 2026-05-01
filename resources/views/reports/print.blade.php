<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Lab Report – {{ $report->patient_name ?? 'Patient' }}</title>
{{-- Load Google Fonts via JS so dompdf (which ignores <script>) never tries to fetch them --}}
<script>
(function(){
    var l=document.createElement('link');
    l.rel='stylesheet';
    l.href='https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Mono:wght@400;500&family=DM+Sans:wght@300;400;500;600&display=swap';
    document.head.appendChild(l);
})();
</script>
<style>
/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   BASE  — used by dompdf (PDF) and @media print both
   Header = 162 px  |  Footer = 84 px
   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
@page {
    size: A4 portrait;
    margin-top:    180px;
    margin-bottom: 84px;
    margin-left:   36px;
    margin-right:  36px;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: Arial, sans-serif;
    font-size: 10.5px;
    line-height: 1.45;
    color: #1a1714;
    background: #fff;
}

/* Action bar hidden in dompdf (no @media screen processed); shown via @media screen below */
#action-bar { display: none; }

/* ── HEADER ──────────────────────────────────────────────────────
   Brand bar ≈ 72px + strip row1 ≈ 43px + strip row2 ≈ 43px + borders ≈ 4px = 162px
   Use 180px so the second patient-strip row is never clipped.
   ─────────────────────────────────────────────────────────────── */
#hdr {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: 180px;
    overflow: hidden;
}

/* 1. Navy brand bar */
.brand-tbl {
    width: 100%;
    border-collapse: collapse;
    background: #1d4e89;
}
.brand-tbl td { padding: 14px 36px; vertical-align: middle; }
.b-left  { width: 50%; }
.b-right { text-align: right; }

.lab-main-name {
    font-family: Georgia, serif;
    font-size: 24px;
    color: #fff;
    letter-spacing: 0.01em;
    line-height: 1.1;
}
.lab-main-tag {
    font-size: 8.5px;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: rgba(255,255,255,0.7);
    margin-top: 4px;
}
.lab-contact-info {
    font-family: 'Courier New', monospace;
    font-size: 10px;
    color: rgba(255,255,255,0.84);
    line-height: 1.75;
    text-align: right;
}

/* 2. Patient info strip — 2 rows × 4 cols */
.pt-strip {
    width: 100%;
    border-collapse: collapse;
    background: #e8eff8;
    border-bottom: 2px solid #1d4e89;
}
.pt-strip td {
    width: 25%;
    padding: 7px 14px;
    vertical-align: top;
    border-right: 1px solid #c8d5e8;
}
.pt-strip td:last-child { border-right: none; }
.pt-row1 td { border-bottom: 1px solid #c8d5e8; }

.pt-lbl {
    font-size: 8px;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #7a7570;
    font-weight: 700;
    margin-bottom: 2px;
}
.pt-val {
    font-family: 'Courier New', monospace;
    font-size: 11px;
    font-weight: 600;
    color: #1d4e89;
}

/* ── FOOTER ──────────────────────────────────────────────────── */
#ftr {
    position: fixed;
    bottom: 0; left: 0; right: 0;
    height: 84px;
    overflow: hidden;
}

/* 1. Legend strip */
.legend-tbl {
    width: 100%;
    border-collapse: collapse;
    background: #f7f5f0;
    border-top: 1px solid #e2ddd6;
}
.legend-tbl td { padding: 7px 36px; font-size: 10px; color: #7a7570; }
.leg-h { color: #c0392b; font-weight: 700; }
.leg-l { color: #d35400; font-weight: 700; }
.leg-n { color: #1e7e4a; font-weight: 700; }

/* 2. Navy footer bar */
.footer-tbl {
    width: 100%;
    border-collapse: collapse;
    background: #1d4e89;
}
.footer-tbl td { padding: 7px 36px; vertical-align: middle; }
.f-disclaimer {
    font-size: 8.5px;
    color: rgba(255,255,255,0.75);
    line-height: 1.55;
    width: 62%;
}
.f-right { text-align: right; }
.pg-num::after {
    content: "Page " counter(page);
    font-size: 9.5px;
    color: rgba(255,255,255,0.65);
    display: block;
    margin-bottom: 1px;
}
.f-sig-name  { font-size: 11px; font-weight: 700; color: #fff; text-align: right; }
.f-sig-title { font-size: 9px; color: rgba(255,255,255,0.72); letter-spacing: 0.3px; text-align: right; }

/* ── CONTENT ─────────────────────────────────────────────────── */
.panel-badge {
    display: block;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #7a7570;
    margin: 20px 0 4px;
    font-weight: 600;
}

.section-title {
    font-family: Georgia, serif;
    font-size: 17px;
    color: #1d4e89;
    border-bottom: 1px solid #e2ddd6;
    padding-bottom: 8px;
    margin-bottom: 14px;
    letter-spacing: 0.02em;
}

.grp-row td {
    background: #eef4fb;
    padding: 5px 14px;
    font-size: 10px;
    font-weight: 700;
    color: #1d4e89;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    border-bottom: 1px solid #d0dff0;
    border-left: 3px solid #1d4e89;
}

/* Result table */
table.rtbl { width: 100%; border-collapse: collapse; font-size: 13px; }

table.rtbl thead tr { background: #1d4e89; }
table.rtbl thead th {
    padding: 9px 14px;
    color: #fff;
    text-align: left;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-weight: 500;
}
table.rtbl thead th.th-result { text-align: center; }

table.rtbl tbody td {
    padding: 9px 14px;
    vertical-align: middle;
    border-bottom: 1px solid #e2ddd6;
    color: #1a1714;
    line-height: 1.4;
}

.param-name { font-weight: 500; color: #2c2925; }

.result-cell {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    font-size: 13px;
    text-align: center;
    white-space: nowrap;
}
.unit-cell, .range-cell {
    font-family: 'Courier New', monospace;
    font-size: 11.5px;
    color: #7a7570;
}

/* Inline H / L badge */
.flag {
    font-size: 10px;
    font-weight: 700;
    padding: 1px 5px;
    margin-left: 5px;
    vertical-align: middle;
    border: 1px solid;
    border-radius: 3px;
}
.flag-H { color: #c0392b; background: #fdecea; border-color: #f5c6c2; }
.flag-L { color: #d35400; background: #fef3ec; border-color: #f9d4b7; }

.result-H { color: #c0392b; }
.result-L { color: #d35400; }
.result-N { color: #1e7e4a; }

.remarks-box {
    margin: 14px 0;
    padding: 10px 14px;
    background: #fffbf0;
    border-left: 4px solid #e6a817;
    font-size: 11px;
    line-height: 1.55;
}

.end-rpt {
    text-align: center;
    font-size: 9px;
    color: #9a9590;
    margin: 22px 0 12px;
    padding: 7px 0;
    border-top: 1px dashed #d8d4cf;
    border-bottom: 1px dashed #d8d4cf;
    letter-spacing: 0.05em;
}

.has-break { page-break-after: always; }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   SCREEN — browser preview
   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
@media screen {
    body { background: #f7f5f0; padding: 64px 0 40px; }

    /* Action bar */
    #action-bar {
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 9999;
        height: 54px;
        background: #1d4e89;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 28px;
        box-shadow: 0 2px 10px rgba(0,0,0,.32);
        font-family: 'DM Sans', Arial, sans-serif;
    }
    .ab-title { color: #fff; font-size: 13px; font-weight: 600; letter-spacing: 0.3px; }
    .ab-btns  { display: flex; gap: 10px; align-items: center; }
    .ab-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 18px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        border: none;
        font-family: 'DM Sans', Arial, sans-serif;
        transition: opacity .15s;
    }
    .ab-btn:hover { opacity: .82; }
    .btn-back     { background: rgba(255,255,255,.15); color: #fff; }
    .btn-print    { background: #27ae60; color: #fff; }
    .btn-download { background: #2471a3; color: #fff; }

    /* A4 page card */
    #page-card {
        max-width: 860px;
        margin: 16px auto 40px;
        background: #fff;
        border: 1px solid #e2ddd6;
        box-shadow: 0 4px 32px rgba(0,0,0,.1);
        padding: 192px 0 94px;
        position: relative;
        overflow: hidden;
    }

    /* Header/footer sit inside the card */
    #hdr {
        position: absolute;
        top: 0; left: 0; right: 0;
        height: auto;
        overflow: visible;
    }
    #ftr {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: auto;
        overflow: visible;
    }

    /* Main content padding */
    #main { padding: 0 36px; }

    /* Page break shown as dashed line in preview */
    .has-break {
        page-break-after: auto;
        border-bottom: 2px dashed #c8d5e8;
        margin-bottom: 26px;
        padding-bottom: 20px;
    }

    /* Row hover (screen only) */
    table.rtbl tbody tr:hover { background: #faf9f7 !important; }

    /* ── Google Font overrides (browser only; dompdf uses Georgia/Courier fallbacks) ── */
    body              { font-family: 'DM Sans', Arial, sans-serif; }
    .lab-main-name    { font-family: 'DM Serif Display', Georgia, serif; }
    .section-title    { font-family: 'DM Serif Display', Georgia, serif; }
    .lab-contact-info,
    .pt-val,
    .result-cell,
    .unit-cell,
    .range-cell       { font-family: 'DM Mono', 'Courier New', monospace; }
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   PRINT — browser Ctrl+P / Print button
   Forces all backgrounds/colours to print exactly like the preview.
   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
@media print {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
    color-adjust: exact !important;

    #action-bar { display: none !important; }
    body { background: #fff !important; padding: 0 !important; }

    #page-card {
        width: auto; margin: 0;
        box-shadow: none; border: none;
        padding: 180px 0 84px;
    }
    #hdr {
        position: fixed !important;
        top: 0; left: 0; right: 0;
        height: 180px;
        overflow: hidden;
    }
    #ftr {
        position: fixed !important;
        bottom: 0; left: 0; right: 0;
        height: 84px;
        overflow: hidden;
    }
    #main { padding: 0 36px; }
    .has-break { page-break-after: always; }

    /* Font overrides for browser print dialog */
    body              { font-family: 'DM Sans', Arial, sans-serif; }
    .lab-main-name    { font-family: 'DM Serif Display', Georgia, serif; }
    .section-title    { font-family: 'DM Serif Display', Georgia, serif; }
    .lab-contact-info,
    .pt-val,
    .result-cell,
    .unit-cell,
    .range-cell       { font-family: 'DM Mono', 'Courier New', monospace; }
}
</style>
</head>
<body>

{{-- ══ ACTION BAR (screen only) ══ --}}
<div id="action-bar">
  <span class="ab-title">
    Lab Report &mdash; {{ strtoupper($report->patient_name ?? 'Patient') }}
  </span>
  <div class="ab-btns">
    <a href="javascript:history.back()" class="ab-btn btn-back">&#8592; Back</a>
    <button onclick="window.print()" class="ab-btn btn-print">&#128424; Print</button>
    <a href="{{ route('user.reports.download', $report) }}" class="ab-btn btn-download">&#11123; Download PDF</a>
  </div>
</div>

<div id="page-card">

{{-- ══ HEADER ══ --}}
<div id="hdr">
@php
  $user   = $report->user;
  $logo64 = '';
  if (!empty($user?->logo)) {
      $lp = public_path('storage/' . $user->logo);
      if (file_exists($lp)) {
          $logo64 = 'data:image/' . pathinfo($lp, PATHINFO_EXTENSION)
                  . ';base64,' . base64_encode(file_get_contents($lp));
      }
  }
@endphp

  {{-- 1. Navy brand bar --}}
  <table class="brand-tbl">
    <tr>
      <td class="b-left">
        @if($logo64)
          <img src="{{ $logo64 }}" alt="Logo"
               style="max-height:40px;max-width:180px;display:block;margin-bottom:4px;">
        @else
          <div class="lab-main-name">{{ $user?->name ?? 'Diagnostic Centre' }}</div>
        @endif
        <div class="lab-main-tag">Trusted Diagnostics &middot; Accurate Results</div>
      </td>
      <td class="b-right">
        <div class="lab-contact-info">
          @if($user?->address){{ $user->address }}<br>@endif
          @if($user?->reference_lab)Ref Lab: {{ $user->reference_lab }}<br>@endif
          @if($user?->mobile){{ $user->mobile }}@endif
          @if($user?->mobile && $user?->email) &middot; @endif
          @if($user?->email){{ $user->email }}@endif
          @if($user?->website)<br>{{ $user->website }}@endif
        </div>
      </td>
    </tr>
  </table>

  {{-- 2. Patient info strip: 2 rows × 4 cols --}}
  <table class="pt-strip">
    <tr class="pt-row1">
      <td>
        <div class="pt-lbl">Patient Name</div>
        <div class="pt-val">{{ strtoupper($report->patient_name ?? '—') }}</div>
      </td>
      <td>
        <div class="pt-lbl">Age / Gender</div>
        <div class="pt-val">
          {{ $report->age ?? '—' }} Yrs / {{ ucfirst($report->gender ?? '—') }}
        </div>
      </td>
      <td>
        <div class="pt-lbl">Sample Collected</div>
        <div class="pt-val">
          {{ $report->test_date
              ? \Carbon\Carbon::parse($report->test_date)->format('d M Y')
              : '—' }}
        </div>
      </td>
      <td>
        <div class="pt-lbl">Report No.</div>
        <div class="pt-val">{{ str_pad($report->id, 6, '0', STR_PAD_LEFT) }}</div>
      </td>
    </tr>
    <tr>
      <td>
        <div class="pt-lbl">Referred By</div>
        <div class="pt-val">{{ $report->referred_by ?? '—' }}</div>
      </td>
      <td>
        <div class="pt-lbl">Client / Hospital</div>
        <div class="pt-val">{{ strtoupper($report->client_name ?? '—') }}</div>
      </td>
      <td>
        <div class="pt-lbl">Report Generated</div>
        <div class="pt-val">{{ now()->format('d M Y') }}</div>
      </td>
      <td>
        <div class="pt-lbl">Lab ID</div>
        <div class="pt-val">{{ $user?->lab_code ?? '—' }}</div>
      </td>
    </tr>
  </table>
</div>{{-- /hdr --}}

{{-- ══ FOOTER ══ --}}
<div id="ftr">
  {{-- Legend strip --}}
  <table class="legend-tbl">
    <tr>
      <td>
        <strong style="color:#1d4e89;font-size:9.5px;text-transform:uppercase;
                        letter-spacing:0.1em;">Legend:</strong>
        &nbsp;&nbsp;
        <span class="leg-h">H</span>&nbsp;= High (above range)
        &nbsp;&nbsp;&nbsp;
        <span class="leg-l">L</span>&nbsp;= Low (below range)
        &nbsp;&nbsp;&nbsp;
        <span class="leg-n">N</span>&nbsp;= Within normal range
      </td>
    </tr>
  </table>

  {{-- Navy footer bar --}}
  <table class="footer-tbl">
    <tr>
      <td class="f-disclaimer">
        {{ $user?->note
          ?? 'This report is subject to the terms and conditions mentioned overleaf. Results are intended for medical use only. Partial reproduction of this report is not permitted.' }}
      </td>
      <td class="f-right">
        <div class="pg-num"></div>
        <div class="f-sig-name">{{ $user?->name ?? 'Authorized Signatory' }}</div>
        <div class="f-sig-title">{{ $user?->qualification ?? 'Medical Director' }}</div>
      </td>
    </tr>
  </table>
</div>{{-- /ftr --}}

{{-- ══ CONTENT ══ --}}
<div id="main">
@php
  $byCategory = $report->results
      ->sortBy('display_order')
      ->groupBy(fn($r) => optional(optional($r->test)->category)->name ?? 'Test Results');
  $catKeys = $byCategory->keys()->toArray();
  $lastKey = end($catKeys);
@endphp

@forelse($byCategory as $catName => $catResults)
  <div class="{{ $catName !== $lastKey ? 'has-break' : '' }}">

    @if($report->panel?->name)
      <div class="panel-badge">{{ $report->panel->name }}</div>
    @endif

    <div class="section-title">{{ $catName }}</div>

    @php
      $byGroup = $catResults->groupBy(fn($r) => optional($r->test)->test_group ?? '');
    @endphp

    @foreach($byGroup as $groupName => $rows)

      <table class="rtbl">
        @if($groupName)
          {{-- Group header as a table row so it spans columns --}}
          <thead>
            <tr>
              <th style="width:44%">
                {{ strtoupper($groupName) }}
              </th>
              <th class="th-result" style="width:16%">Result</th>
              <th style="width:14%">Unit</th>
              <th style="width:26%">Reference Range</th>
            </tr>
          </thead>
        @else
          <thead>
            <tr>
              <th style="width:44%">Test Parameter</th>
              <th class="th-result" style="width:16%">Result</th>
              <th style="width:14%">Unit</th>
              <th style="width:26%">Reference Range</th>
            </tr>
          </thead>
        @endif
        <tbody>
          @foreach($rows as $i => $result)
            @php
              $raw  = $result->value ?? '';
              $num  = is_numeric($raw) ? floatval($raw) : null;
              $rng  = trim($result->reference_range ?? '');
              $flag = '';
              $resCls = '';
              if ($rng && $num !== null) {
                if (preg_match_all('/\d+(?:\.\d+)?/', $rng, $m) && count($m[0]) >= 2) {
                  [$lo, $hi] = [floatval($m[0][0]), floatval($m[0][1])];
                  if      ($num < $lo) { $flag = 'L'; $resCls = 'result-L'; }
                  elseif  ($num > $hi) { $flag = 'H'; $resCls = 'result-H'; }
                  else                   $resCls = 'result-N';
                } elseif (preg_match('/>\s*(\d+(?:\.\d+)?)/', $rng, $m2)) {
                  if ($num <= floatval($m2[1])) { $flag = 'L'; $resCls = 'result-L'; }
                  else $resCls = 'result-N';
                } elseif (preg_match('/<\s*(\d+(?:\.\d+)?)/', $rng, $m2)) {
                  if ($num >= floatval($m2[1])) { $flag = 'H'; $resCls = 'result-H'; }
                  else $resCls = 'result-N';
                }
              }
              $rowBg = ($i % 2 === 0) ? '#ffffff' : '#faf9f7';
            @endphp
            <tr style="background:{{ $rowBg }};">
              <td class="param-name" style="width:44%">
                {{ $result->test_name ?? '—' }}
              </td>
              <td class="result-cell {{ $resCls }}" style="width:16%">
                {{ $raw !== '' ? $raw : '—' }}
                @if($flag)
                  <span class="flag flag-{{ $flag }}">{{ $flag }}</span>
                @endif
              </td>
              <td class="unit-cell"  style="width:14%">{{ $result->unit ?? '' }}</td>
              <td class="range-cell" style="width:26%">{{ $rng ?: '—' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

    @endforeach

    @if(!empty($report->remarks) && $catName === $lastKey)
      <div class="remarks-box">
        <strong>Clinical Remarks:</strong>&nbsp;{{ $report->remarks }}
      </div>
    @endif

    <div class="end-rpt">&mdash;&nbsp;&nbsp; End of Report &nbsp;&nbsp;&mdash;</div>

  </div>
@empty
  <p style="text-align:center;padding:48px;color:#9a9590;font-style:italic;font-size:13px;">
    No test results recorded for this report.
  </p>
@endforelse
</div>{{-- /main --}}

</div>{{-- /page-card --}}
</body>
</html>
