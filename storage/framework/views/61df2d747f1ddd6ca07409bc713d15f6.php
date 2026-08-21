<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Lab Report – <?php echo e($report->patient_name ?? 'Patient'); ?></title>

<script>
(function(){
    var l=document.createElement('link');
    l.rel='stylesheet';
    l.href='https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Playfair+Display:wght@600;700&family=JetBrains+Mono:wght@400;500;600&display=swap';
    document.head.appendChild(l);
})();
</script>
<?php $t = $theme ?? ['primary'=>'#1a3461','primaryDark'=>'#122548','primaryLite'=>'#e8f0fb','primaryBorder'=>'#c2d4ec','primaryTint'=>'#f0f5fc','legendBg'=>'#f2f6fc','legendBorder'=>'#d4e0f0','accent'=>'#e8a020']; ?>
<style>
/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   BASE — dompdf uses these (no @media support). Colors output by PHP.
   Header ≈ 186px  |  Footer ≈ 88px
   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
@page {
    size: A4 portrait;
    margin-top:    190px;
    margin-bottom: 92px;
    margin-left:   40px;
    margin-right:  40px;
}
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:Arial,sans-serif; font-size:10.5px; line-height:1.5; color:#1c2333; background:#fff; }
#action-bar { display:none; }

/* ── HEADER ── */
#hdr { position:fixed; top:0; left:0; right:0; height:186px; overflow:hidden; }

.brand-tbl { width:100%; border-collapse:collapse; background:<?php echo e($t['primary']); ?>; border-bottom:4px solid <?php echo e($t['accent']); ?>; }
.brand-tbl td { padding:13px 36px; vertical-align:middle; }
.b-left { width:55%; } .b-right { text-align:right; }

.lab-main-name { font-family:Georgia,serif; font-size:26px; color:#fff; letter-spacing:.01em; line-height:1.1; }
.lab-sub-tag   { font-size:7.5px; text-transform:uppercase; letter-spacing:.18em; color:<?php echo e($t['accent']); ?>; margin-top:5px; }
.rpt-badge     { display:inline-block; font-size:7px; text-transform:uppercase; letter-spacing:.2em; color:rgba(255,255,255,.5); border:1px solid rgba(255,255,255,.22); padding:2px 7px; border-radius:2px; margin-top:6px; }
.lab-contact-info { font-family:'Courier New',monospace; font-size:9.5px; color:rgba(255,255,255,.78); line-height:1.85; text-align:right; }

.pt-strip { width:100%; border-collapse:collapse; background:<?php echo e($t['primaryLite']); ?>; border-bottom:2px solid <?php echo e($t['primary']); ?>; }
.pt-strip td { width:25%; padding:7px 16px; vertical-align:top; border-right:1px solid <?php echo e($t['primaryBorder']); ?>; }
.pt-strip td:last-child { border-right:none; }
.pt-row1 td  { border-bottom:1px solid <?php echo e($t['primaryBorder']); ?>; }
.pt-lbl { font-size:7.5px; text-transform:uppercase; letter-spacing:.12em; color:#7080a0; font-weight:700; margin-bottom:3px; }
.pt-val { font-family:'Courier New',monospace; font-size:11.5px; font-weight:700; color:<?php echo e($t['primary']); ?>; }

/* ── FOOTER ── */
#ftr { position:fixed; bottom:0; left:0; right:0; height:88px; overflow:hidden; }

.legend-tbl { width:100%; border-collapse:collapse; background:<?php echo e($t['legendBg']); ?>; border-top:1px solid <?php echo e($t['legendBorder']); ?>; }
.legend-tbl td { padding:6px 36px; font-size:10px; color:#6878a0; }
.leg-strong { color:<?php echo e($t['primary']); ?>; font-size:9px; text-transform:uppercase; letter-spacing:.12em; }
.leg-h { color:#c0192b; font-weight:800; } .leg-l { color:#b84a05; font-weight:800; } .leg-n { color:#15803d; font-weight:800; }

.footer-tbl { width:100%; border-collapse:collapse; background:<?php echo e($t['primary']); ?>; border-top:3px solid <?php echo e($t['accent']); ?>; }
.footer-tbl td { padding:8px 36px; vertical-align:middle; }
.f-disclaimer { font-size:8px; color:rgba(255,255,255,.6); line-height:1.65; width:60%; }
.f-right { text-align:right; }
.pg-num::after { content:"Page " counter(page); font-size:9px; color:rgba(255,255,255,.5); display:block; margin-bottom:2px; }
.f-sig-name  { font-size:12px; font-weight:700; color:#fff; text-align:right; }
.f-sig-title { font-size:9px; color:<?php echo e($t['accent']); ?>; letter-spacing:.35px; text-align:right; }

/* ── CONTENT ── */
.stat-bar { width:100%; border-collapse:collapse; background:<?php echo e($t['legendBg']); ?>; border:1px solid <?php echo e($t['primaryBorder']); ?>; margin-bottom:20px; }
.stat-bar td { padding:10px 16px; text-align:center; border-right:1px solid <?php echo e($t['primaryBorder']); ?>; vertical-align:middle; }
.stat-bar td:last-child { border-right:none; }
.stat-num { font-family:'Courier New',monospace; font-size:22px; font-weight:700; display:block; line-height:1; }
.stat-lbl { font-size:8px; text-transform:uppercase; letter-spacing:.12em; color:#7080a0; display:block; margin-top:3px; }
.stat-total { color:<?php echo e($t['primary']); ?>; } .stat-high { color:#c0192b; } .stat-low { color:#b84a05; } .stat-norm { color:#15803d; }

.panel-badge { display:inline-block; font-size:9px; text-transform:uppercase; letter-spacing:.14em; color:<?php echo e($t['primary']); ?>; background:<?php echo e($t['primaryTint']); ?>; border:1px solid <?php echo e($t['primaryBorder']); ?>; padding:3px 10px; border-radius:3px; margin:18px 0 6px; }

.section-title { font-family:Georgia,serif; font-size:17px; color:<?php echo e($t['primary']); ?>; padding:9px 14px 9px 16px; margin-bottom:12px; background:<?php echo e($t['primaryTint']); ?>; border-left:5px solid <?php echo e($t['accent']); ?>; }

table.rtbl { width:100%; border-collapse:collapse; margin-bottom:2px; }
table.rtbl thead tr { background:<?php echo e($t['primary']); ?>; }
table.rtbl thead th { padding:9px 14px; color:#fff; text-align:left; font-size:9.5px; text-transform:uppercase; letter-spacing:.1em; font-weight:600; }
table.rtbl thead th.th-result { text-align:center; }

.grp-hdr td { background:<?php echo e($t['primaryDark']); ?>; padding:5px 14px 5px 16px; font-size:9.5px; font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:.08em; border-left:4px solid <?php echo e($t['accent']); ?>; }

table.rtbl tbody td { padding:9px 14px; vertical-align:middle; border-bottom:1px solid #e4ecf8; color:#1c2333; line-height:1.4; }
.param-name { font-weight:600; color:#1c2333; font-size:11px; }
.result-cell { font-family:'Courier New',monospace; font-weight:700; font-size:14.5px; text-align:center; white-space:nowrap; }
.unit-cell  { font-family:'Courier New',monospace; font-size:11px; color:#8896b0; }
.range-cell { font-family:'Courier New',monospace; font-size:10.5px; color:#8896b0; }

.flag { font-size:9.5px; font-weight:800; padding:2px 7px; margin-left:5px; vertical-align:middle; border-radius:10px; letter-spacing:.02em; }
.flag-H { color:#fff; background:#c0192b; } .flag-L { color:#fff; background:#b84a05; }
.result-H { color:#c0192b; } .result-L { color:#b84a05; } .result-N { color:#15803d; }

.interp-row td { padding:4px 14px 10px 30px; background:#f0fdf4; border-bottom:1px solid #e4ecf8; border-left:3px solid #16a34a; font-size:10.5px; color:#166534; line-height:1.6; font-style:italic; }
.interp-label  { font-weight:700; font-style:normal; color:#15803d; margin-right:5px; }

.remarks-box { margin:16px 0 4px; padding:11px 16px; background:#fffbeb; border-left:5px solid <?php echo e($t['accent']); ?>; font-size:11px; line-height:1.65; color:#3d2e00; }

.end-rpt { text-align:center; font-size:9px; color:#a0aac0; margin:24px 0 12px; padding:7px 0; border-top:1px dashed #c8d8ee; border-bottom:1px dashed #c8d8ee; letter-spacing:.08em; text-transform:uppercase; }
.has-break { page-break-after:always; }

/* Interpretation hidden in screen by default */
@media screen { .interp-row { display:none; } #page-card.show-interp .interp-row { display:table-row; } }

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   SCREEN — browser preview with CSS variable overrides for live theme
   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
@media screen {
    /* ── CSS custom properties (JS updates these for real-time theme changes) ── */
    :root {
        --c-primary:        <?php echo e($t['primary']); ?>;
        --c-primary-dark:   <?php echo e($t['primaryDark']); ?>;
        --c-primary-lite:   <?php echo e($t['primaryLite']); ?>;
        --c-primary-border: <?php echo e($t['primaryBorder']); ?>;
        --c-primary-tint:   <?php echo e($t['primaryTint']); ?>;
        --c-legend-bg:      <?php echo e($t['legendBg']); ?>;
        --c-legend-border:  <?php echo e($t['legendBorder']); ?>;
        --c-accent:         <?php echo e($t['accent']); ?>;
    }

    /* ── All color-dependent rules now reference variables ── */
    body { background:#dce6f5; padding:60px 0 48px; font-family:'Inter',Arial,sans-serif; }

    /* Action bar */
    #action-bar {
        position:fixed; top:0; left:0; right:0; z-index:9999; height:56px;
        background:var(--c-primary);
        display:flex; align-items:center; justify-content:space-between; padding:0 20px;
        box-shadow:0 2px 20px rgba(0,0,0,.4);
        border-bottom:3px solid var(--c-accent);
        font-family:'Inter',Arial,sans-serif;
    }
    .ab-left  { display:flex; align-items:center; gap:10px; flex-shrink:0; }
    .ab-dot   { width:8px; height:8px; border-radius:50%; background:var(--c-accent); flex-shrink:0; }
    .ab-title { color:#fff; font-size:13px; font-weight:600; letter-spacing:.3px; }
    .ab-sub   { color:rgba(255,255,255,.45); font-size:10.5px; }

    /* Theme swatches */
    .ab-themes { display:flex; align-items:center; gap:5px; padding:0 14px; border-left:1px solid rgba(255,255,255,.15); border-right:1px solid rgba(255,255,255,.15); flex-shrink:0; }
    .ab-themes-label { color:rgba(255,255,255,.4); font-size:9px; text-transform:uppercase; letter-spacing:.14em; margin-right:4px; white-space:nowrap; }
    .theme-sw {
        width:20px; height:20px; border-radius:50%; cursor:pointer;
        border:2px solid transparent; display:inline-block; transition:all .18s;
        flex-shrink:0;
    }
    .theme-sw:hover  { transform:scale(1.25); border-color:rgba(255,255,255,.6); }
    .theme-sw.active { border-color:#fff; box-shadow:0 0 0 2px rgba(255,255,255,.4); transform:scale(1.1); }
    .color-pick-wrap {
        position:relative; width:22px; height:22px; border-radius:50%;
        background:rgba(255,255,255,.12); border:2px dashed rgba(255,255,255,.35);
        cursor:pointer; display:flex; align-items:center; justify-content:center;
        font-size:11px; transition:all .18s; overflow:hidden;
    }
    .color-pick-wrap:hover { border-color:rgba(255,255,255,.7); transform:scale(1.2); }
    .color-pick-wrap input[type=color] { position:absolute; opacity:0; width:100%; height:100%; cursor:pointer; border:none; padding:0; }

    /* Action buttons */
    .ab-btns { display:flex; gap:7px; align-items:center; flex-shrink:0; }
    .ab-btn {
        display:inline-flex; align-items:center; gap:5px; padding:6px 14px;
        border-radius:6px; font-size:11.5px; font-weight:600; cursor:pointer;
        text-decoration:none; border:none; font-family:'Inter',Arial,sans-serif;
        transition:all .18s; letter-spacing:.2px; line-height:1; white-space:nowrap;
    }
    .ab-btn:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,.28); }
    .btn-back     { background:rgba(255,255,255,.1); color:rgba(255,255,255,.85); border:1px solid rgba(255,255,255,.2); }
    .btn-print    { background:#16a34a; color:#fff; }
    .btn-download { background:#2563eb; color:#fff; }
    .btn-interp   { background:rgba(255,255,255,.08); color:rgba(255,255,255,.65); border:1px solid rgba(255,255,255,.18); }
    .btn-interp.active { background:var(--c-accent); color:#fff; border-color:transparent; }

    /* Page card */
    #page-card {
        max-width:860px; margin:12px auto 48px; background:#fff;
        border:1px solid #c8d8ee; border-radius:2px;
        box-shadow:0 8px 48px rgba(26,52,97,.18),0 2px 8px rgba(0,0,0,.08);
        padding:200px 0 98px; position:relative; overflow:hidden;
    }
    #hdr { position:absolute; top:0; left:0; right:0; height:auto; overflow:visible; }
    #ftr { position:absolute; bottom:0; left:0; right:0; height:auto; overflow:visible; }
    #main { padding:0 40px; }

    .has-break { page-break-after:auto; border-bottom:2px dashed #b8ccee; margin-bottom:32px; padding-bottom:22px; }
    table.rtbl tbody tr:not(.interp-row):hover { filter:brightness(.965); cursor:default; }

    /* Font overrides */
    .lab-main-name { font-family:'Playfair Display',Georgia,serif; }
    .section-title { font-family:'Playfair Display',Georgia,serif; border-radius:0 4px 4px 0; }
    .lab-contact-info,.pt-val,.result-cell,.unit-cell,.range-cell { font-family:'JetBrains Mono','Courier New',monospace; }

    /* CSS variable overrides for all theme-colored elements */
    .brand-tbl    { background:var(--c-primary) !important; border-bottom-color:var(--c-accent) !important; }
    .lab-sub-tag  { color:var(--c-accent) !important; }
    .pt-strip     { background:var(--c-primary-lite) !important; border-bottom-color:var(--c-primary) !important; }
    .pt-strip td  { border-color:var(--c-primary-border) !important; }
    .pt-val       { color:var(--c-primary) !important; }
    .legend-tbl   { background:var(--c-legend-bg) !important; border-top-color:var(--c-legend-border) !important; }
    .leg-strong   { color:var(--c-primary) !important; }
    .footer-tbl   { background:var(--c-primary) !important; border-top-color:var(--c-accent) !important; }
    .f-sig-title  { color:var(--c-accent) !important; }
    .stat-bar     { background:var(--c-legend-bg) !important; border-color:var(--c-primary-border) !important; }
    .stat-bar td  { border-color:var(--c-primary-border) !important; }
    .stat-total   { color:var(--c-primary) !important; }
    .panel-badge  { color:var(--c-primary) !important; background:var(--c-primary-tint) !important; border-color:var(--c-primary-border) !important; }
    .section-title { color:var(--c-primary) !important; background:var(--c-primary-tint) !important; border-left-color:var(--c-accent) !important; }
    table.rtbl thead tr { background:var(--c-primary) !important; }
    .grp-hdr td   { background:var(--c-primary-dark) !important; border-left-color:var(--c-accent) !important; }
    .remarks-box  { border-left-color:var(--c-accent) !important; }

    .stat-bar { border-radius:8px; overflow:hidden; box-shadow:0 1px 8px rgba(26,52,97,.1); }
}

/* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
   PRINT — window.print() / Ctrl+P
   ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
@media print {
    -webkit-print-color-adjust:exact !important;
    print-color-adjust:exact !important;
    color-adjust:exact !important;

    #action-bar { display:none !important; }
    body { background:#fff !important; padding:0 !important; }
    #page-card { width:auto; margin:0; box-shadow:none; border:none; padding:190px 0 92px; }
    #hdr { position:fixed !important; top:0; left:0; right:0; height:186px; overflow:hidden; }
    #ftr { position:fixed !important; bottom:0; left:0; right:0; height:88px; overflow:hidden; }
    #main { padding:0 40px; }
    .has-break { page-break-after:always; }
    .lab-main-name  { font-family:'Playfair Display',Georgia,serif; }
    .section-title  { font-family:'Playfair Display',Georgia,serif; }
    .lab-contact-info,.pt-val,.result-cell,.unit-cell,.range-cell { font-family:'JetBrains Mono','Courier New',monospace; }
}
</style>
</head>
<body>


<div id="action-bar">
  
  <div class="ab-left">
    <div class="ab-dot"></div>
    <div>
      <div class="ab-title"><?php echo e(strtoupper($report->patient_name ?? 'Patient')); ?></div>
      <div class="ab-sub">
        #<?php echo e(str_pad($report->id, 6, '0', STR_PAD_LEFT)); ?>

        &middot; <?php echo e($report->test_date ? \Carbon\Carbon::parse($report->test_date)->format('d M Y') : ''); ?>

      </div>
    </div>
  </div>

  
  <div class="ab-themes">
    <span class="ab-themes-label">Theme</span>
    <span class="theme-sw <?php echo e(($selectedTheme ?? 'navy') === 'navy'    ? 'active' : ''); ?>"
          data-theme="navy"    style="background:#1a3461" title="Navy Blue"></span>
    <span class="theme-sw <?php echo e(($selectedTheme ?? 'navy') === 'teal'    ? 'active' : ''); ?>"
          data-theme="teal"    style="background:#0e7490" title="Teal"></span>
    <span class="theme-sw <?php echo e(($selectedTheme ?? 'navy') === 'emerald' ? 'active' : ''); ?>"
          data-theme="emerald" style="background:#166534" title="Emerald"></span>
    <span class="theme-sw <?php echo e(($selectedTheme ?? 'navy') === 'purple'  ? 'active' : ''); ?>"
          data-theme="purple"  style="background:#5b21b6" title="Purple"></span>
    <span class="theme-sw <?php echo e(($selectedTheme ?? 'navy') === 'rose'    ? 'active' : ''); ?>"
          data-theme="rose"    style="background:#9f1239" title="Rose"></span>
    <span class="theme-sw <?php echo e(($selectedTheme ?? 'navy') === 'slate'   ? 'active' : ''); ?>"
          data-theme="slate"   style="background:#1e293b" title="Charcoal"></span>
    
    <label class="color-pick-wrap" title="Pick any color">
      &#127912;
      <input type="color" id="custom-color" value="<?php echo e($t['primary']); ?>">
    </label>
  </div>

  
  <div class="ab-btns">
    <a href="javascript:history.back()" class="ab-btn btn-back">&#8592; Back</a>
    <button onclick="window.print()" class="ab-btn btn-print">&#9113; Print</button>
    <button onclick="toggleInterp()" id="interp-btn" class="ab-btn btn-interp">&#128065; Interpretation</button>
    <a id="download-btn" href="<?php echo e(route('user.reports.download', $report)); ?>?theme=<?php echo e($selectedTheme ?? 'navy'); ?>" class="ab-btn btn-download">&#11123; Download PDF</a>
  </div>
</div>

<script>
/* ── Theme definitions (mirror of PHP getTheme) ── */
var THEMES = {
    navy:    { primary:'#1a3461', primaryDark:'#122548', primaryLite:'#e8f0fb', primaryBorder:'#c2d4ec', primaryTint:'#f0f5fc', legendBg:'#f2f6fc', legendBorder:'#d4e0f0', accent:'#e8a020' },
    teal:    { primary:'#0e7490', primaryDark:'#0a5b72', primaryLite:'#e0f7fc', primaryBorder:'#b0dce8', primaryTint:'#f0fbfd', legendBg:'#f0fbfd', legendBorder:'#b0dce8', accent:'#f59e0b' },
    emerald: { primary:'#166534', primaryDark:'#0e4524', primaryLite:'#dcfce7', primaryBorder:'#a7d4b8', primaryTint:'#f0fdf4', legendBg:'#f0fdf4', legendBorder:'#a7d4b8', accent:'#f59e0b' },
    purple:  { primary:'#5b21b6', primaryDark:'#4c1d95', primaryLite:'#ede9fe', primaryBorder:'#c4b5fd', primaryTint:'#f5f3ff', legendBg:'#f5f3ff', legendBorder:'#c4b5fd', accent:'#f59e0b' },
    rose:    { primary:'#9f1239', primaryDark:'#7f0f2e', primaryLite:'#ffe4e6', primaryBorder:'#fca5a5', primaryTint:'#fff5f5', legendBg:'#fff5f5', legendBorder:'#fca5a5', accent:'#f59e0b' },
    slate:   { primary:'#1e293b', primaryDark:'#0f172a', primaryLite:'#f1f5f9', primaryBorder:'#cbd5e1', primaryTint:'#f8fafc', legendBg:'#f8fafc', legendBorder:'#e2e8f0', accent:'#e8a020' },
};

/* ── Color math for custom picker ── */
function hexToRgb(hex) {
    var r = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return r ? {r:parseInt(r[1],16), g:parseInt(r[2],16), b:parseInt(r[3],16)} : null;
}
function mix(hex, tr, tg, tb, amt) {
    var c = hexToRgb(hex); if (!c) return hex;
    var ch = function(a,b){ return ('0'+Math.min(255,Math.max(0,Math.round(a+(b-a)*amt))).toString(16)).slice(-2); };
    return '#'+ch(c.r,tr)+ch(c.g,tg)+ch(c.b,tb);
}
function customTheme(primary) {
    return {
        primary:       primary,
        primaryDark:   mix(primary,0,0,0,0.22),
        primaryLite:   mix(primary,255,255,255,0.88),
        primaryBorder: mix(primary,255,255,255,0.65),
        primaryTint:   mix(primary,255,255,255,0.94),
        legendBg:      mix(primary,255,255,255,0.96),
        legendBorder:  mix(primary,255,255,255,0.78),
        accent:        '#e8a020',
    };
}

/* ── Apply theme to CSS variables ── */
var downloadBase = '<?php echo e(route("user.reports.download", $report)); ?>';
var activeThemeName = '<?php echo e($selectedTheme ?? "navy"); ?>';

function applyTheme(t, name) {
    var r = document.documentElement;
    r.style.setProperty('--c-primary',         t.primary);
    r.style.setProperty('--c-primary-dark',     t.primaryDark);
    r.style.setProperty('--c-primary-lite',     t.primaryLite);
    r.style.setProperty('--c-primary-border',   t.primaryBorder);
    r.style.setProperty('--c-primary-tint',     t.primaryTint);
    r.style.setProperty('--c-legend-bg',        t.legendBg);
    r.style.setProperty('--c-legend-border',    t.legendBorder);
    r.style.setProperty('--c-accent',           t.accent);

    /* Update download URL so PDF uses the same theme */
    var url = downloadBase + '?theme=' + (name || 'custom');
    if (!name || name === 'custom') url += '&color=' + encodeURIComponent(t.primary.replace('#',''));
    document.getElementById('download-btn').href = url;
}

/* ── Preset swatches ── */
document.querySelectorAll('.theme-sw').forEach(function(sw) {
    sw.addEventListener('click', function() {
        var name = this.dataset.theme;
        activeThemeName = name;
        applyTheme(THEMES[name], name);
        document.querySelectorAll('.theme-sw').forEach(function(s){ s.classList.remove('active'); });
        this.classList.add('active');
        document.getElementById('custom-color').value = THEMES[name].primary;
    });
});

/* ── Custom color picker ── */
document.getElementById('custom-color').addEventListener('input', function() {
    var t = customTheme(this.value);
    activeThemeName = 'custom';
    applyTheme(t, null);
    document.querySelectorAll('.theme-sw').forEach(function(s){ s.classList.remove('active'); });
});

/* ── Interpretation toggle ── */
function toggleInterp() {
    var card = document.getElementById('page-card');
    var btn  = document.getElementById('interp-btn');
    var on   = card.classList.toggle('show-interp');
    btn.innerHTML = on ? '&#128065; Interpretation: <strong>ON</strong>' : '&#128065; Interpretation';
    btn.classList.toggle('active', on);
}
</script>

<div id="page-card">


<div id="hdr">
<?php
  $user   = $report->user;
  $logo64 = '';
  if (!empty($user?->logo)) {
      $lp = public_path('storage/' . $user->logo);
      if (file_exists($lp)) {
          $logo64 = 'data:image/' . pathinfo($lp, PATHINFO_EXTENSION)
                  . ';base64,' . base64_encode(file_get_contents($lp));
      }
  }
?>

  <table class="brand-tbl">
    <tr>
      <td class="b-left">
        <?php if($logo64): ?>
          <img src="<?php echo e($logo64); ?>" alt="Logo" style="max-height:44px;max-width:190px;display:block;margin-bottom:4px;">
        <?php else: ?>
          <div class="lab-main-name"><?php echo e($user?->name ?? 'Diagnostic Centre'); ?></div>
        <?php endif; ?>
        <div class="lab-sub-tag">Trusted Diagnostics &middot; Accurate Results</div>
        <div class="rpt-badge">Laboratory Report</div>
      </td>
      <td class="b-right">
        <div class="lab-contact-info">
          <?php if($user?->address): ?><?php echo e($user->address); ?><br><?php endif; ?>
          <?php if($user?->reference_lab): ?>Ref Lab: <?php echo e($user->reference_lab); ?><br><?php endif; ?>
          <?php if($user?->mobile): ?><?php echo e($user->mobile); ?><?php endif; ?>
          <?php if($user?->mobile && $user?->email): ?> &middot; <?php endif; ?>
          <?php if($user?->email): ?><?php echo e($user->email); ?><?php endif; ?>
          <?php if($user?->website): ?><br><?php echo e($user->website); ?><?php endif; ?>
        </div>
      </td>
    </tr>
  </table>

  <table class="pt-strip">
    <tr class="pt-row1">
      <td><div class="pt-lbl">Patient Name</div><div class="pt-val"><?php echo e(strtoupper($report->patient_name ?? '—')); ?></div></td>
      <td><div class="pt-lbl">Age / Gender</div><div class="pt-val"><?php echo e($report->age ?? '—'); ?> Yrs / <?php echo e(ucfirst($report->gender ?? '—')); ?></div></td>
      <td>
        <div class="pt-lbl">Sample Collected</div>
        <div class="pt-val"><?php echo e($report->test_date ? \Carbon\Carbon::parse($report->test_date)->format('d M Y') : '—'); ?></div>
      </td>
      <td><div class="pt-lbl">Report No.</div><div class="pt-val"><?php echo e(str_pad($report->id, 6, '0', STR_PAD_LEFT)); ?></div></td>
    </tr>
    <tr>
      <td><div class="pt-lbl">Referred By</div><div class="pt-val"><?php echo e($report->referred_by ?? '—'); ?></div></td>
      <td><div class="pt-lbl">Client / Hospital</div><div class="pt-val"><?php echo e(strtoupper($report->client_name ?? '—')); ?></div></td>
      <td><div class="pt-lbl">Report Generated</div><div class="pt-val"><?php echo e(now()->format('d M Y')); ?></div></td>
      <td><div class="pt-lbl">Lab ID</div><div class="pt-val"><?php echo e($user?->lab_code ?? '—'); ?></div></td>
    </tr>
  </table>
</div>


<div id="ftr">
  <table class="legend-tbl">
    <tr>
      <td>
        <strong class="leg-strong">Legend:</strong>
        &nbsp;&nbsp;
        <span class="leg-h">&#8593;&nbsp;H</span>&nbsp;= High
        &nbsp;&nbsp;
        <span class="leg-l">&#8595;&nbsp;L</span>&nbsp;= Low
        &nbsp;&nbsp;
        <span class="leg-n">&#10003;&nbsp;N</span>&nbsp;= Normal range
      </td>
    </tr>
  </table>
  <table class="footer-tbl">
    <tr>
      <td class="f-disclaimer">
        <?php echo e($user?->note ?? 'This report is subject to the terms and conditions mentioned overleaf. Results are intended for medical use only. Partial reproduction of this report is not permitted.'); ?>

      </td>
      <td class="f-right">
        <div class="pg-num"></div>
        <div class="f-sig-name"><?php echo e($user?->name ?? 'Authorized Signatory'); ?></div>
        <div class="f-sig-title"><?php echo e($user?->qualification ?? 'Medical Director'); ?></div>
      </td>
    </tr>
  </table>
</div>


<div id="main">
<?php
  $statTotal = 0; $statH = 0; $statL = 0; $statN = 0;
  foreach ($report->results->sortBy('display_order') as $_r) {
      $statTotal++;
      $_raw = $_r->value ?? '';
      $_num = is_numeric($_raw) ? floatval($_raw) : null;
      $_rng = trim($_r->reference_range ?? '');
      if ($_rng && $_num !== null) {
          if (preg_match_all('/\d+(?:\.\d+)?/', $_rng, $_m) && count($_m[0]) >= 2) {
              [$_lo,$_hi] = [floatval($_m[0][0]),floatval($_m[0][1])];
              if ($_num < $_lo) $statL++; elseif ($_num > $_hi) $statH++; else $statN++;
          } elseif (preg_match('/>\s*(\d+(?:\.\d+)?)/', $_rng, $_m2)) {
              $_num <= floatval($_m2[1]) ? $statL++ : $statN++;
          } elseif (preg_match('/<\s*(\d+(?:\.\d+)?)/', $_rng, $_m2)) {
              $_num >= floatval($_m2[1]) ? $statH++ : $statN++;
          }
      }
  }
  $byCategory = $report->results
      ->sortBy('display_order')
      ->groupBy(fn($r) => optional(optional($r->test)->category)->name ?? 'Test Results');
  $catKeys = $byCategory->keys()->toArray();
  $lastKey = end($catKeys);
?>

<?php if($statTotal > 0): ?>
<table class="stat-bar">
  <tr>
    <td><span class="stat-num stat-total"><?php echo e($statTotal); ?></span><span class="stat-lbl">Total Tests</span></td>
    <td><span class="stat-num stat-high"><?php echo e($statH); ?></span><span class="stat-lbl">High &#8593;</span></td>
    <td><span class="stat-num stat-low"><?php echo e($statL); ?></span><span class="stat-lbl">Low &#8595;</span></td>
    <td><span class="stat-num stat-norm"><?php echo e($statN); ?></span><span class="stat-lbl">Normal &#10003;</span></td>
  </tr>
</table>
<?php endif; ?>

<?php $__empty_1 = true; $__currentLoopData = $byCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catName => $catResults): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
  <div class="<?php echo e($catName !== $lastKey ? 'has-break' : ''); ?>">

    <?php if($report->panel?->name): ?>
      <div class="panel-badge"><?php echo e($report->panel->name); ?></div>
    <?php endif; ?>

    <div class="section-title"><?php echo e($catName); ?></div>

    <?php $byGroup = $catResults->groupBy(fn($r) => optional($r->test)->test_group ?? ''); ?>

    <?php $__currentLoopData = $byGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $rows): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <table class="rtbl">
        <thead>
          <?php if($groupName): ?>
          <tr class="grp-hdr">
            <td colspan="4"><?php echo e(strtoupper($groupName)); ?></td>
          </tr>
          <?php endif; ?>
          <tr>
            <th style="width:42%">Test Parameter</th>
            <th class="th-result" style="width:16%">Result</th>
            <th style="width:14%">Unit</th>
            <th style="width:28%">Reference Range</th>
          </tr>
        </thead>
        <tbody>
          <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
              $raw  = $result->value ?? '';
              $num  = is_numeric($raw) ? floatval($raw) : null;
              $rng  = trim($result->reference_range ?? '');
              $flag = ''; $resCls = '';
              if ($rng && $num !== null) {
                if (preg_match_all('/\d+(?:\.\d+)?/', $rng, $m) && count($m[0]) >= 2) {
                  [$lo,$hi] = [floatval($m[0][0]),floatval($m[0][1])];
                  if      ($num < $lo) { $flag='L'; $resCls='result-L'; }
                  elseif  ($num > $hi) { $flag='H'; $resCls='result-H'; }
                  else                   $resCls='result-N';
                } elseif (preg_match('/>\s*(\d+(?:\.\d+)?)/', $rng, $m2)) {
                  if ($num <= floatval($m2[1])) { $flag='L'; $resCls='result-L'; } else $resCls='result-N';
                } elseif (preg_match('/<\s*(\d+(?:\.\d+)?)/', $rng, $m2)) {
                  if ($num >= floatval($m2[1])) { $flag='H'; $resCls='result-H'; } else $resCls='result-N';
                }
              }
              if ($flag==='H')         { $rowBg='#fff5f5'; $borderL='border-left:3px solid #c0192b;'; }
              elseif ($flag==='L')     { $rowBg='#fffbeb'; $borderL='border-left:3px solid #b84a05;'; }
              elseif ($resCls==='result-N') { $rowBg=($i%2===0)?'#ffffff':'#f5faf0'; $borderL=''; }
              else                     { $rowBg=($i%2===0)?'#ffffff':'#f8faff';  $borderL=''; }
            ?>
            <tr style="background:<?php echo e($rowBg); ?>;">
              <td class="param-name" style="width:42%;<?php echo e($borderL); ?>"><?php echo e($result->test_name ?? '—'); ?></td>
              <td class="result-cell <?php echo e($resCls); ?>" style="width:16%">
                <?php echo e($raw !== '' ? $raw : '—'); ?>

                <?php if($flag==='H'): ?><span class="flag flag-H">&#8593;&nbsp;H</span><?php endif; ?>
                <?php if($flag==='L'): ?><span class="flag flag-L">&#8595;&nbsp;L</span><?php endif; ?>
              </td>
              <td class="unit-cell"  style="width:14%"><?php echo e($result->unit ?? ''); ?></td>
              <td class="range-cell" style="width:28%"><?php echo e($rng ?: '—'); ?></td>
            </tr>
            <?php if(!empty($result->interpretation)): ?>
            <tr class="interp-row">
              <td colspan="4"><span class="interp-label">Interpretation:</span><?php echo e($result->interpretation); ?></td>
            </tr>
            <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
      </table>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php if(!empty($report->remarks) && $catName === $lastKey): ?>
      <div class="remarks-box"><strong>Clinical Remarks:</strong>&nbsp;<?php echo e($report->remarks); ?></div>
    <?php endif; ?>

    <div class="end-rpt">&mdash;&nbsp; End of Report &nbsp;&mdash;</div>
  </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
  <p style="text-align:center;padding:56px;color:#a0aac0;font-style:italic;font-size:13px;">No test results recorded for this report.</p>
<?php endif; ?>
</div>
</div>
</body>
</html>
<?php /**PATH C:\lab-management\resources\views/reports/print.blade.php ENDPATH**/ ?>