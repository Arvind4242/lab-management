<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ $report->report_name ?? 'Medical Lab Report' }}</title>
<style>

/*
 ┌──────────────────────────────────────────────────────┐
 │  HOW THIS WORKS IN DOMPDF                            │
 │                                                      │
 │  @page { margin-top: Hpx; margin-bottom: Fpx }       │
 │    → DomPDF leaves Hpx gap at top of every page     │
 │    → DomPDF leaves Fpx gap at bottom of every page  │
 │                                                      │
 │  #hdr { position:fixed; top:0; height:Hpx }          │
 │    → Header fills the top margin gap exactly        │
 │                                                      │
 │  #ftr { position:fixed; bottom:0; height:Fpx }       │
 │    → Footer fills the bottom margin gap exactly     │
 │                                                      │
 │  Body content flows normally between the margins.   │
 │  Short content → gap between content and footer.    │
 │  Long content  → new page, same header+footer.      │
 └──────────────────────────────────────────────────────┘
*/

/* ── PAGE MARGINS ─────────────────────────────────────────────
   These values MUST exactly match #hdr height and #ftr height.
   Measure: header = 120px, footer = 112px
──────────────────────────────────────────────────────────────── */
@page {
    size:          A4 portrait;
    margin-top:    120px;
    margin-bottom: 112px;
    margin-left:   0;
    margin-right:  0;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: Arial, Helvetica, sans-serif;
    font-size:   11px;
    line-height: 1.4;
    color:       #000;
    background:  #fff;
}

/* ── HEADER : fixed, top:0, height = @page margin-top ──────── */
#hdr {
    position: fixed;
    top:      0;
    left:     0;
    right:    0;
    height:   120px;
    background: #ffffff;
    overflow: hidden;
}

.accent-top {
    height: 5px; background: #0d1b2a;
    font-size: 0; line-height: 0;
}
.hdr-tbl {
    width: 100%; border-collapse: collapse;
    background: #faf8f4; border-bottom: 2px solid #e5e5e5;
}
.hdr-tbl td { vertical-align: middle; }
.h-logo    { width: 42%; padding: 10px 20px 8px; vertical-align: middle; }
.h-sep     { width: 2px; background: #e5e5e5; border-left: 2px solid #e5e5e5; padding: 0; }
.h-contact { width: 56%; padding: 10px 20px 8px 16px; vertical-align: middle; text-align: right; }

.logo-img { max-width: 158px; max-height: 50px; display: block; }
.lab-nm   { font-size: 16px; font-weight: bold; color: #0d1b2a; line-height: 1.1; }
.lab-nm span { color: #1a6b7c; }
.lab-tg   { font-size: 7px; color: #6b7280; letter-spacing: 2px; text-transform: uppercase; margin-top: 2px; }
.barcode  { width: 88px; height: 13px; border: 1px solid #ccc; margin-top: 5px; background: #eee; }
.c-inner  {
    font-size: 8px; line-height: 1.7; color: #1f2937;
    border-left: 3px solid #1a6b7c; padding-left: 8px;
    display: inline-block; text-align: left;
}
.cl { color: #1a6b7c; font-weight: bold; font-size: 7px; text-transform: uppercase; }

.bnr-tbl { width: 100%; border-collapse: collapse; background: #0d1b2a; }
.bnr-tbl td { padding: 5px 20px; vertical-align: middle; }
.bnr-tbl td:last-child { text-align: right; }
.b-title  { font-size: 10px; font-weight: bold; color: #fff; letter-spacing: 2.5px; text-transform: uppercase; }
.b-badge  { background: #c9a84c; color: #0d1b2a; font-size: 7px; font-weight: bold; padding: 2px 8px; letter-spacing: 1px; text-transform: uppercase; }

/* ── FOOTER : fixed, bottom:0, height = @page margin-bottom ── */
#ftr {
    position: fixed;
    bottom:   0;
    left:     0;
    right:    0;
    height:   112px;
    background: #ffffff;
    overflow: hidden;
}

.note-banner {
    background: #1a6b7c; color: #fff;
    padding: 4px 20px; font-size: 7.5px; line-height: 1.5;
}
.note-banner b { font-weight: bold; }
.ftr-tbl {
    width: 100%; border-collapse: collapse;
    background: #faf8f4; border-top: 1.5px solid #e5e5e5;
}
.ftr-tbl td { padding: 7px 20px 8px; vertical-align: bottom; }
.f-qr  { width: 68px; }
.f-note { text-align: center; }
.f-sig  { text-align: right; }
.qr-box { width: 52px; height: 52px; border: 1.5px solid #0d1b2a; background: #f0f0f0; }
.qr-lbl { font-size: 6px; color: #6b7280; letter-spacing: 1px; text-transform: uppercase; margin-top: 3px; font-weight: bold; }
.fn-txt   { font-size: 6.5px; color: #9ca3af; line-height: 1.6; }
.fn-txt b { display: block; color: #6b7280; font-size: 7px; margin-bottom: 1px; }
.fn-pg    { margin-top: 3px; font-size: 6px; color: #ccc; letter-spacing: 1px; text-transform: uppercase; }
.sig-line  { width: 100px; height: 1px; background: #0d1b2a; margin: 0 0 3px auto; }
.sig-img   { max-width: 100px; max-height: 38px; display: block; margin: 0 0 3px auto; }
.doc-nm    { font-size: 10px; font-weight: bold; color: #0d1b2a; text-align: right; }
.doc-ttl   { font-size: 6.5px; color: #1a6b7c; text-transform: uppercase; letter-spacing: 1.5px; font-weight: bold; text-align: right; margin-top: 1px; }
.auth-stmp { font-size: 6px; color: #9ca3af; border: 1px solid #e5e5e5; padding: 1px 5px; display: inline-block; margin-top: 2px; }
.accent-bot { height: 5px; background: #c9a84c; }

/* ── CONTENT : normal flow, DomPDF places between @page margins  */
#main { background: #fff; }

.pat-tbl { width: 100%; border-collapse: collapse; border-bottom: 1.5px solid #e5e5e5; }
.pat-tbl td { vertical-align: top; padding: 10px 20px; }
.p-div { width: 1px; border-left: 1px dashed #ddd; padding: 0 !important; }
.sec-lbl {
    font-size: 6.5px; font-weight: bold; letter-spacing: 2px;
    text-transform: uppercase; color: #1a6b7c;
    margin-bottom: 7px; padding-bottom: 3px; border-bottom: 1px solid #e5e5e5;
}
.i-tbl { width: 100%; border-collapse: collapse; }
.i-tbl td { padding: 2px 0; vertical-align: top; }
.ik     { font-size: 8px; color: #9ca3af; font-weight: bold; text-transform: uppercase; letter-spacing: 0.3px; width: 90px; white-space: nowrap; }
.iv     { font-size: 10px; color: #1f2937; font-weight: bold; }
.iv-big { font-size: 12.5px; color: #0d1b2a; font-weight: bold; }
.pill   { font-size: 6.5px; font-weight: bold; padding: 1px 5px; letter-spacing: 1px; text-transform: uppercase; margin-left: 3px; }
.pill-m  { background: #2a8fa3; color: #fff; }
.pill-f  { background: #c45c8a; color: #fff; }
.pill-o  { background: #6b7280; color: #fff; }
.pill-ok { background: #d1fae5; color: #1e7e5e; }
.ttl-lab {
    text-align: center; font-size: 11px; font-weight: bold;
    color: #0d1b2a; letter-spacing: 2px; text-transform: uppercase;
    padding: 5px 20px; border-bottom: 1px solid #e5e5e5; background: #f7f7f7;
}
.ttl-test {
    text-align: center; font-size: 8px; font-weight: bold;
    color: #6b7280; letter-spacing: 3px; text-transform: uppercase;
    padding: 3px 20px 4px; border-bottom: 1px solid #e5e5e5;
}
.r-wrap { padding: 8px 20px 5px; }
.r-tbl  { width: 100%; border-collapse: collapse; font-size: 10px; }
.r-tbl thead tr { background: #0d1b2a; }
.r-tbl thead th {
    padding: 6px 8px; font-size: 7px; font-weight: bold;
    letter-spacing: 1.5px; text-transform: uppercase;
    color: #fff; text-align: left; border: none;
}
.r-tbl thead th.tc { text-align: center; }
.r-tbl tbody tr        { border-bottom: 1px solid #e5e5e5; }
.r-tbl tbody tr.even   { background: #f7f7f7; }
.r-tbl tbody td        { padding: 6px 8px; vertical-align: middle; border: none; color: #1f2937; }
.t-nm  { font-weight: bold; color: #0d1b2a; }
.tc    { text-align: center; }
.r-ok  { color: #1e7e5e; font-weight: bold; font-size: 11px; }
.r-hi  { color: #cc0000; font-weight: bold; font-size: 11px; }
.r-lo  { color: #d97706; font-weight: bold; font-size: 11px; }
.r-val { font-weight: bold; font-size: 10px; }
.r-unt { color: #6b7280; font-size: 8.5px; }
.r-rng { color: #6b7280; font-size: 8.5px; text-align: center; }
.flag-H { background: #fde8e8; color: #cc0000; font-size: 7px; font-weight: bold; padding: 1px 5px; }
.flag-L { background: #fef3c7; color: #92400e; font-size: 7px; font-weight: bold; padding: 1px 5px; }
.interp-wrap {
    margin: 0 20px 5px; background: #fafafa; border: 1px solid #e5e5e5;
    padding: 6px 10px; font-size: 9px; line-height: 1.6;
}
.interp-lbl { font-weight: bold; color: #0d1b2a; text-transform: uppercase; font-size: 7.5px; letter-spacing: 1px; margin-top: 4px; margin-bottom: 2px; }
.end-rpt {
    text-align: center; padding: 7px 0 5px; margin: 0 20px;
    color: #9ca3af; font-size: 7px; letter-spacing: 3px;
    text-transform: uppercase; font-weight: bold;
    border-top: 1px dashed #e5e5e5;
}

/* ── BROWSER ONLY ─────────────────────────────────────────────
   DomPDF ignores @media screen — 100% safe
──────────────────────────────────────────────────────────────── */
@media screen {
    body { background: #ccc; padding: 24px 0 40px; }

    #page-card {
        width: 794px;
        min-height: 1123px;
        margin: 0 auto;
        background: #fff;
        box-shadow: 0 6px 40px rgba(0,0,0,0.22);
        display: flex;
        flex-direction: column;
    }

    #hdr {
        position: sticky;
        top: 0;
        height: auto;
        z-index: 100;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        flex-shrink: 0;
    }
    #main {
        flex: 1;
    }
    #ftr {
        position: sticky;
        bottom: 0;
        height: auto;
        z-index: 100;
        box-shadow: 0 -2px 8px rgba(0,0,0,0.08);
        flex-shrink: 0;
        margin-top: auto;
    }
}
</style>
</head>
<body>
<div id="page-card">

{{-- ══ HEADER ══════════════════════════════════════════════════ --}}
<div id="hdr">
  <div class="accent-top"></div>
  <table class="hdr-tbl">
    <tr>
      <td class="h-logo">
        @php
          $logo64 = '';
          if (!empty(auth()->user()->logo)) {
            $lp = public_path('storage/' . auth()->user()->logo);
            if (file_exists($lp)) {
              $logo64 = 'data:image/' . pathinfo($lp, PATHINFO_EXTENSION)
                      . ';base64,' . base64_encode(file_get_contents($lp));
            }
          }
        @endphp
        @if($logo64)
          <img src="{{ $logo64 }}" alt="Logo" class="logo-img">
        @else
          <div class="lab-nm">{{ auth()->user()?->name ?? 'Lab' }} <span>Diagnostics</span></div>
          <div class="lab-tg">Precision in Every Report</div>
        @endif
        <div class="barcode"></div>
      </td>
      <td class="h-sep"></td>
      <td class="h-contact">
        <div class="c-inner">
          @if(auth()->user()?->address)
            <div><span class="cl">Main Lab:</span> {{ auth()->user()->address }}</div>
          @endif
          <div><span class="cl">Customer Care:</span> {{ auth()->user()?->mobile ?? '—' }}</div>
          @if(auth()->user()?->reference_lab)
            <div><span class="cl">Reference Lab:</span> {{ auth()->user()->reference_lab }}</div>
          @endif
          <div>In Front of M.Y. Hospital, Gate No. 2, Indore (M.P.)</div>
          <div><span class="cl">Email:</span> {{ auth()->user()?->email ?? '—' }}</div>
          @if(auth()->user()?->website)
            <div><span class="cl">Web:</span> {{ auth()->user()->website }}</div>
          @endif
        </div>
      </td>
    </tr>
  </table>
  <table class="bnr-tbl">
    <tr>
      <td><span class="b-title">Pathology Report</span></td>
      <td>
        @php $lc = auth()->user()->lab_code ?? $report->lab_code ?? $report->id ?? null; @endphp
        @if($lc)<span class="b-badge">Lab Code: {{ $lc }}</span>@endif
      </td>
    </tr>
  </table>
</div>{{-- /hdr --}}

{{-- ══ CONTENT ═════════════════════════════════════════════════ --}}
<div id="main">
  <table class="pat-tbl">
    <tr>
      <td style="width:48%">
        <div class="sec-lbl">Patient Details</div>
        <table class="i-tbl">
          <tr><td class="ik">Patient Name</td><td class="iv-big">{{ strtoupper($report->patient_name ?? '—') }}</td></tr>
          <tr>
            <td class="ik">Age / Gender</td>
            <td class="iv">
              {{ $report->age ?? '—' }} yrs
              @php $g = strtolower($report->gender ?? ''); @endphp
              @if($g==='male')        <span class="pill pill-m">MALE</span>
              @elseif($g==='female')  <span class="pill pill-f">FEMALE</span>
              @else                   <span class="pill pill-o">{{ strtoupper($report->gender ?? '—') }}</span>
              @endif
            </td>
          </tr>
          <tr><td class="ik">Referred By</td><td class="iv">Dr. {{ strtoupper($report->referred_by ?? 'Self') }}</td></tr>
          <tr><td class="ik">Client Name</td><td class="iv">{{ strtoupper($report->client_name ?? '—') }}</td></tr>
        </table>
      </td>
      <td class="p-div"></td>
      <td style="width:48%">
        <div class="sec-lbl">Report Details</div>
        <table class="i-tbl">
          <tr>
            <td class="ik">Sample Reg. At</td>
            <td class="iv">
              @php $d1 = $report->collection_date ?? $report->test_date ?? $report->created_at ?? null; @endphp
              {{ $d1 ? \Carbon\Carbon::parse($d1)->format('d/m/Y h:i A') : '—' }}
            </td>
          </tr>
          <tr>
            <td class="ik">Reported At</td>
            <td class="iv">
              @php $d2 = $report->reporting_date ?? $report->updated_at ?? $report->created_at ?? null; @endphp
              {{ $d2 ? \Carbon\Carbon::parse($d2)->format('d/m/Y h:i A') : '—' }}
            </td>
          </tr>
          <tr><td class="ik">Report Type</td><td class="iv">Pathology</td></tr>
          <tr><td class="ik">Status</td><td class="iv"><span class="pill pill-ok">&#10003; Final</span></td></tr>
        </table>
      </td>
    </tr>
  </table>

  @php $labTitle = optional(\App\Models\Lab::find(auth()->user()?->lab_id))->name ?? 'Authorized Signatory'; @endphp
  <div class="ttl-lab">{{ strtoupper($labTitle) }}</div>

  @if($report->results->isNotEmpty())
    @php
      $first    = $report->results->first();
      $secTitle = optional($report->panel)->name
               ?: optional(optional($first)->test)->name
               ?: optional(optional($first->test)->category)->name
               ?: ($first->category_name ?? 'Test Results');
    @endphp
    <div class="ttl-test">{{ strtoupper($secTitle) }}</div>
  @endif

  <div class="r-wrap">
    <table class="r-tbl">
      <thead>
        <tr>
          <th style="width:37%">Test Name</th>
          <th style="width:15%" class="tc">Result</th>
          <th style="width:12%">Unit</th>
          <th style="width:24%" class="tc">Biological Reference Interval</th>
          <th style="width:12%" class="tc">Flag</th>
        </tr>
      </thead>
      <tbody>
        @php $interps = collect(); $ri = 0; @endphp
        @forelse($report->results as $result)
          @php
            $raw  = $result->value ?? '';
            $num  = is_numeric($raw) ? floatval($raw) : null;
            $rng  = trim($result->reference_range ?? '');
            $cls  = ''; $flag = '';
            if ($rng && $num !== null) {
              if (preg_match_all('/\d+(?:\.\d+)?/', $rng, $m) && count($m[0]) >= 2) {
                $lo = floatval($m[0][0]); $hi = floatval($m[0][1]);
                if      ($num < $lo) { $cls='r-lo'; $flag='L'; }
                elseif  ($num > $hi) { $cls='r-hi'; $flag='H'; }
                else                 { $cls='r-ok'; }
              } elseif (preg_match('/>\s*(\d+(?:\.\d+)?)/', $rng, $m)) {
                $cls=($num<=floatval($m[1]))?'r-lo':'r-ok';
                $flag=($num<=floatval($m[1]))?'L':'';
              } elseif (preg_match('/<\s*(\d+(?:\.\d+)?)/', $rng, $m)) {
                $cls=($num>=floatval($m[1]))?'r-hi':'r-ok';
                $flag=($num>=floatval($m[1]))?'H':'';
              }
            }
            $grp = optional($result->test)->test_group;
            if ($grp) {
              $gt = \App\Models\Test::where('test_group',$grp)->first();
              if ($gt && (!empty($gt->interpretation)||!empty($gt->comment))) $interps->push($gt);
            }
            $rc = ($ri++ % 2 === 1) ? 'even' : '';
          @endphp
          <tr class="{{ $rc }}">
            <td class="t-nm">{{ $result->test_name ?? '—' }}</td>
            <td class="tc"><span class="{{ $cls ?: 'r-val' }}">{{ $raw ?: '—' }}</span></td>
            <td class="r-unt">{{ $result->unit ?? '—' }}</td>
            <td class="r-rng">{{ $rng ?: '—' }}</td>
            <td class="tc">
              @if($flag==='H')     <span class="flag-H">H</span>
              @elseif($flag==='L') <span class="flag-L">L</span>
              @else                <span style="color:#d1d5db">&mdash;</span>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="5" style="text-align:center;padding:14px;color:#9ca3af;font-style:italic">No test results recorded.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($interps->isNotEmpty())
    <div class="interp-wrap">
      @foreach($interps->unique('test_group') as $gt)
        @if(!empty($gt->interpretation))
          <div class="interp-lbl">{{ strtoupper($gt->test_group) }} — INTERPRETATION</div>
          <p>{{ $gt->interpretation }}</p>
        @endif
        @if(!empty($gt->comment))
          <div class="interp-lbl">{{ strtoupper($gt->test_group) }} — COMMENT</div>
          <p>{{ $gt->comment }}</p>
        @endif
      @endforeach
    </div>
  @endif

  <div class="end-rpt">&mdash; End of Report &mdash;</div>
</div>{{-- /main --}}

{{-- ══ FOOTER ══════════════════════════════════════════════════ --}}
<div id="ftr">
  @if(auth()->user()?->note)
    <div class="note-banner"><b>Note:</b> {{ auth()->user()->note }}</div>
  @endif
  <table class="ftr-tbl">
    <tr>
      <td class="f-qr">
        <div class="qr-box"></div>
        <div class="qr-lbl">Scan to Validate</div>
      </td>
      <td class="f-note">
        <div class="fn-txt">
          <b>Important Note</b>
          Results should be interpreted in clinical context.<br>
          This report is electronically generated &amp; valid without physical signature.<br>
          For queries please contact our customer care.
        </div>
        <div class="fn-pg">Generated: {{ now()->format('d/m/Y h:i A') }}</div>
      </td>
      <td class="f-sig">
        @php
          $sig64 = '';
          if (auth()->user()->digital_signature) {
            $sp = public_path('storage/' . auth()->user()->digital_signature);
            if (file_exists($sp)) {
              $sig64 = 'data:image/' . pathinfo($sp, PATHINFO_EXTENSION)
                     . ';base64,' . base64_encode(file_get_contents($sp));
            }
          }
        @endphp
        @if($sig64)
          <img src="{{ $sig64 }}" alt="Signature" class="sig-img">
        @else
          <div class="sig-line"></div>
        @endif
        <div class="doc-nm">{{ auth()->user()?->name ?? 'Authorized Signatory' }}</div>
        <div class="doc-ttl">{{ auth()->user()?->qualification ?? 'Medical Director' }}</div>
        <div class="auth-stmp">Authorized Signatory</div>
      </td>
    </tr>
  </table>
  <div class="accent-bot"></div>
</div>{{-- /ftr --}}

</div>{{-- /page-card --}}
</body>
</html>
