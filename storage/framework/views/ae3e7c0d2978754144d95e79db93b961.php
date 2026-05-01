<?php $__env->startSection('title', 'New Report'); ?>
<?php $__env->startSection('page-title', 'New Report'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.test-row { animation: fadeIn 0.2s ease; }
@keyframes fadeIn { from { opacity:0; transform:translateY(-4px); } to { opacity:1; transform:none; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('user.reports.store')); ?>" id="reportForm">
<?php echo csrf_field(); ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    
    <div class="lg:col-span-1 space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 mb-4">Patient Details</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Patient Name *</label>
                    <input type="text" name="patient_name" value="<?php echo e(old('patient_name')); ?>" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none" placeholder="Full name">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Age</label>
                        <input type="text" name="age" value="<?php echo e(old('age')); ?>"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="e.g. 35Y">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Gender</label>
                        <select name="gender" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="male" <?php echo e(old('gender')=='male'?'selected':''); ?>>Male</option>
                            <option value="female" <?php echo e(old('gender')=='female'?'selected':''); ?>>Female</option>
                            <option value="other" <?php echo e(old('gender')=='other'?'selected':''); ?>>Other</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Referred By</label>
                    <input type="text" name="referred_by" value="<?php echo e(old('referred_by')); ?>"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Dr. Name">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Client / Sample Source</label>
                    <input type="text" name="client_name" value="<?php echo e(old('client_name')); ?>"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Hospital / Clinic name">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Test Date</label>
                    <input type="date" name="test_date" value="<?php echo e(old('test_date', date('Y-m-d'))); ?>" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 mb-4">Load from Panel</h3>
            <select id="panelSelect" name="test_panel_id"
                class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none mb-3">
                <option value="">— Select panel —</option>
                <?php $__currentLoopData = $panels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($id); ?>" data-category="<?php echo e($panelCategories[$id] ?? ''); ?>"><?php echo e($name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="button" id="loadPanelBtn" class="w-full py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-medium rounded-xl transition-colors">
                Load Panel Tests
            </button>
        </div>

        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Load from Package</h3>
                <a href="<?php echo e(route('user.packages.create')); ?>" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">+ New</a>
            </div>
            <select id="packageSelect"
                class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none mb-3">
                <option value="">— Select package —</option>
                <?php $__currentLoopData = $myPackages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pkg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($pkg->id); ?>">⭐ <?php echo e($pkg->name); ?> (<?php echo e($pkg->tests_count); ?>)</option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php $__currentLoopData = $globalPackages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pkg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($pkg->id); ?>"><?php echo e($pkg->name); ?> (<?php echo e($pkg->tests_count); ?>)</option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="button" id="loadPackageBtn" class="w-full py-2.5 bg-purple-50 hover:bg-purple-100 text-purple-700 text-sm font-medium rounded-xl transition-colors">
                Load Package Tests
            </button>
        </div>

        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-900 mb-4">Add Individual Test</h3>
            <select id="testSelect" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none mb-3">
                <option value="">— Search test —</option>
                <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="button" id="addTestBtn" class="w-full py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                Add Test
            </button>
        </div>
    </div>

    
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Test Results</h3>
                <span id="testCount" class="text-xs text-gray-500">0 tests</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="testsTable">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="text-left px-4 py-2.5 font-semibold">Test / Parameter</th>
                            <th class="text-left px-4 py-2.5 font-semibold w-28">Value</th>
                            <th class="text-left px-4 py-2.5 font-semibold w-20">Unit</th>
                            <th class="text-left px-4 py-2.5 font-semibold w-32">Ref. Range</th>
                            <th class="px-4 py-2.5 w-10"></th>
                        </tr>
                    </thead>
                    <tbody id="testsBody">
                        <tr id="emptyRow">
                            <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                                Select a panel or add individual tests above
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between">
                <button type="button" id="clearBtn" class="text-sm text-red-500 hover:text-red-700 font-medium">Clear all</button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                    Save Report
                </button>
            </div>
        </div>
    </div>
</div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
let testIndex = 0;

/* ── helpers ─────────────────────────────────────────────── */
function inp(type, name, value, cls) {
    const el = document.createElement('input');
    el.type = type; el.name = name; el.value = value || '';
    if (cls) el.className = cls;
    return el;
}

function td(cls) {
    const el = document.createElement('td');
    el.className = cls || 'px-4 py-2.5';
    return el;
}

function updateCount() {
    const rows = document.querySelectorAll('#testsBody tr.test-row');
    const n = rows.length;
    document.getElementById('testCount').textContent = n + ' test' + (n !== 1 ? 's' : '');
    const emptyRow = document.getElementById('emptyRow');
    if (emptyRow) emptyRow.style.display = n > 0 ? 'none' : '';
}

function getRef(data) {
    const gender = (document.querySelector('[name="gender"]') || {}).value || 'male';
    if (gender === 'female') return data.reference_range_female || data.reference_range_male || '';
    if (gender === 'other')  return data.reference_range_other  || data.reference_range_male || '';
    return data.reference_range_male || '';
}

function addTestRow(data) {
    const i   = testIndex++;
    const ref = getRef(data);
    const row = document.createElement('tr');
    row.className = 'test-row hover:bg-gray-50/60 transition-colors';

    /* col 1 – test name */
    const c1 = td();
    c1.appendChild(inp('hidden', 'tests[' + i + '][test_id]', data.id));
    c1.appendChild(inp('hidden', 'tests[' + i + '][test_name]', data.name));
    c1.appendChild(inp('hidden', 'tests[' + i + '][parameter_name]', data.parameter_name || data.name));
    const nameSpan = document.createElement('span');
    nameSpan.className = 'text-sm font-medium text-gray-900';
    nameSpan.textContent = data.name || '';
    c1.appendChild(nameSpan);
    if (data.parameter_name && data.parameter_name !== data.name) {
        const sub = document.createElement('p');
        sub.className = 'text-xs text-gray-400';
        sub.textContent = data.parameter_name;
        c1.appendChild(sub);
    }

    /* col 2 – value input */
    const c2   = td();
    const vInp = inp('text', 'tests[' + i + '][value]', data.value || '',
        'w-full px-2 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-indigo-400 focus:border-indigo-400 outline-none');
    vInp.placeholder = '—';
    c2.appendChild(vInp);

    /* col 3 – unit */
    const c3 = td();
    c3.appendChild(inp('hidden', 'tests[' + i + '][unit]', data.unit || ''));
    const unitSpan = document.createElement('span');
    unitSpan.className = 'text-xs text-gray-500';
    unitSpan.textContent = data.unit || '—';
    c3.appendChild(unitSpan);

    /* col 4 – ref range */
    const c4 = td();
    c4.appendChild(inp('hidden', 'tests[' + i + '][reference_range]', ref));
    const refSpan = document.createElement('span');
    refSpan.className = 'text-xs text-gray-500';
    refSpan.textContent = ref || '—';
    c4.appendChild(refSpan);

    /* col 5 – remove button */
    const c5  = td('px-4 py-2.5 text-center');
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'text-gray-300 hover:text-red-500 transition-colors';
    btn.title = 'Remove';
    btn.innerHTML = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
    btn.addEventListener('click', function () { row.remove(); updateCount(); });
    c5.appendChild(btn);

    row.append(c1, c2, c3, c4, c5);
    document.getElementById('testsBody').appendChild(row);
    updateCount();
}

/* ── fetch helpers ───────────────────────────────────────── */
function setLoading(btn, loading) {
    btn.disabled = loading;
    btn.style.opacity = loading ? '0.6' : '1';
    btn.textContent = loading ? 'Loading…' : btn.dataset.label;
}

function loadTests(url, btn, clearFirst) {
    if (!btn.dataset.label) btn.dataset.label = btn.textContent;
    setLoading(btn, true);
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(function(tests) {
            const arr = Array.isArray(tests) ? tests : Object.values(tests);
            if (clearFirst) {
                document.querySelectorAll('#testsBody tr.test-row').forEach(function(r) { r.remove(); });
                testIndex = 0;
            }
            if (arr.length === 0) {
                showToast('No tests found for the selected item.', 'warn');
            } else {
                arr.forEach(addTestRow);
                showToast(arr.length + ' test(s) loaded.', 'ok');
            }
        })
        .catch(function(err) {
            console.error('loadTests error:', err);
            showToast('Failed to load tests. Please try again.', 'err');
        })
        .finally(function() { setLoading(btn, false); });
}

/* ── toast notification ──────────────────────────────────── */
function showToast(msg, type) {
    const colors = { ok: '#10b981', warn: '#f59e0b', err: '#ef4444' };
    const t = document.createElement('div');
    t.style.cssText = 'position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;padding:.75rem 1.25rem;border-radius:.75rem;color:#fff;font-size:.875rem;font-weight:600;box-shadow:0 4px 12px rgba(0,0,0,.15);transition:opacity .4s;background:' + (colors[type] || colors.ok);
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function() { t.style.opacity = '0'; setTimeout(function() { t.remove(); }, 400); }, 2500);
}

/* ── button events ───────────────────────────────────────── */
document.getElementById('loadPanelBtn').addEventListener('click', function() {
    const id = document.getElementById('panelSelect').value;
    if (!id) { showToast('Please select a panel first.', 'warn'); return; }
    loadTests('/dashboard/ajax/panel-tests/' + id, this, true);
});

document.getElementById('loadPackageBtn').addEventListener('click', function() {
    const id = document.getElementById('packageSelect').value;
    if (!id) { showToast('Please select a package first.', 'warn'); return; }
    loadTests('/dashboard/ajax/package-tests/' + id, this, true);
});

document.getElementById('addTestBtn').addEventListener('click', function() {
    const sel = document.getElementById('testSelect');
    if (!sel.value) { showToast('Please select a test first.', 'warn'); return; }
    loadTests('/dashboard/ajax/test/' + sel.value, this, false);
    sel.value = '';
});

document.getElementById('clearBtn').addEventListener('click', function() {
    if (!document.querySelectorAll('#testsBody tr.test-row').length) return;
    document.querySelectorAll('#testsBody tr.test-row').forEach(function(r) { r.remove(); });
    testIndex = 0;
    updateCount();
});

/* ── auto-load from ?package= query param ────────────────── */
(function() {
    const pkg = new URLSearchParams(window.location.search).get('package');
    if (!pkg) return;
    const sel = document.getElementById('packageSelect');
    if (sel) sel.value = pkg;
    fetch('/dashboard/ajax/package-tests/' + pkg, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(r) { return r.ok ? r.json() : []; })
        .then(function(tests) {
            (Array.isArray(tests) ? tests : Object.values(tests)).forEach(addTestRow);
        })
        .catch(function() {});
})();

updateCount();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\lab\lab-management\resources\views/user/reports/create.blade.php ENDPATH**/ ?>