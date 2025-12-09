<?php $__env->startSection('content'); ?>
<a href="http://127.0.0.1:8000/admin/reports" class="btn btn-warning mb-3">
    <i class="bi bi-arrow-left"></i> Back
</a>

<div class="container my-4">
    <div class="report-container">
        <div class="d-flex align-items-center mb-4">
            <div class="report-icon">🧾</div>
            <h4 class="mb-0 ms-3">Create Lab Report</h4>
        </div>

        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('reports.store')); ?>" method="POST" id="reportForm">
            <?php echo csrf_field(); ?>

            <!-- Patient Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary bg-gradient text-white">
                    <i class="bi bi-person-fill me-2"></i>Patient Details
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Patient Name <span class="text-danger">*</span></label>
                            <input type="text" name="patient_name" class="form-control form-control-lg" placeholder="Enter patient name" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Age <span class="text-danger">*</span></label>
                            <input type="number" name="age" class="form-control form-control-lg" placeholder="Age" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select form-select-lg" required>
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Referred By</label>
                            <input type="text" name="referred_by" class="form-control form-control-lg" placeholder="Doctor's name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Client Name</label>
                            <input type="text" name="client_name" class="form-control form-control-lg" placeholder="Client name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Test Date <span class="text-danger">*</span></label>
                            <input type="date" name="test_date" class="form-control form-control-lg" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Category & Panel Selection -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success bg-gradient text-white">
                    <i class="bi bi-clipboard2-pulse-fill me-2"></i>Select Test Category, Panel & Tests
                </div>
                <div class="card-body">
                    <!-- Category Filter -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-funnel-fill text-primary"></i> Filter by Test Category
                        </label>
                        <select id="category_filter" class="form-select form-select-lg">
                            <option value="">🔍 All Categories</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Test Panel Selection (Single) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">📋 Select Test Panel (Optional - One Only)</label>
                        <select id="panel_selector" class="form-select form-select-lg">
                            <option value="">-- No Panel Selected --</option>
                            <?php $__currentLoopData = $panels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="panel_<?php echo e($id); ?>" data-category-id="<?php echo e($panelCategories[$id] ?? ''); ?>">
                                    <?php echo e($name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <small class="form-text text-muted mt-2 d-block">
                            <i class="bi bi-info-circle"></i> Select one panel or choose individual tests below
                        </small>
                    </div>

                    <!-- Individual Tests Selection (Multiple) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">🧪 Select Individual Tests (Multiple)</label>
                        <select id="test_selector" class="form-select form-select-lg" multiple>
                            <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="test_<?php echo e($test->id); ?>" data-category-id="<?php echo e($test->category_id ?? ''); ?>">
                                    <?php echo e($test->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <small class="form-text text-muted mt-2 d-block">
                            <i class="bi bi-info-circle"></i> Type to search, select multiple tests
                        </small>
                    </div>

                    <input type="hidden" name="test_panel_id" id="test_panel_id">

                    <!-- Selected Items Display -->
                    <div id="selected-items" class="mt-3"></div>
                </div>
            </div>

            <!-- Test Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info bg-gradient text-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-table me-2"></i>Test Results</span>
                    <small class="badge bg-light text-dark">💡 Drag to reorder tests</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%"></th>
                                    <th width="28%">Test Name</th>
                                    <th width="22%">Result Value</th>
                                    <th width="15%">Unit</th>
                                    <th width="25%">Reference Range</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody id="test-list">
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-search fs-1 d-block mb-2"></i>
                                        Select test panels or tests to begin
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between gap-3">
                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="window.location.href='http://127.0.0.1:8000/admin/reports'">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-danger btn-lg" id="clearBtn">
                        <i class="bi bi-trash me-2"></i>Clear All
                    </button>
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        <i class="bi bi-save me-2"></i>Save Report
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
/* --------- PAGE BACKGROUND --------- */
body {
    background: radial-gradient(circle at top left, #e0f2fe 0%, #e5e7eb 40%, #f3f4f6 100%);
    min-height: 100vh;
    padding: 24px 0;
}

/* --------- MAIN CONTAINER --------- */
.container.my-4 {
    max-width: 1150px;
}

.report-container {
    background: #ffffff;
    padding: 28px 30px;
    border-radius: 18px;
    box-shadow: 0 18px 45px rgba(15, 23, 42, 0.16);
    border: 1px solid rgba(148, 163, 184, 0.35);
}

/* --------- HEADER / TITLE --------- */
.report-icon {
    font-size: 42px;
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: linear-gradient(135deg, #0ea5e9 0%, #22c55e 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    box-shadow: 0 10px 25px rgba(14, 165, 233, 0.4);
    animation: pulse-soft 2.4s infinite;
}

@keyframes pulse-soft {
    0%, 100% { transform: scale(1); box-shadow: 0 10px 25px rgba(14, 165, 233, 0.4); }
    50% { transform: scale(1.06); box-shadow: 0 14px 32px rgba(14, 165, 233, 0.55); }
}

.report-container h4 {
    font-weight: 700;
    letter-spacing: 0.4px;
    color: #0f172a;
}

/* --------- CARDS --------- */
.card {
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}

.card:hover {
    transform: translateY(-2px);
    border-color: #cbd5f5;
    box-shadow: 0 10px 26px rgba(15, 23, 42, 0.12);
}

.card-header {
    font-weight: 600;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
    padding-top: 0.7rem;
    padding-bottom: 0.7rem;
}

/* Soften header colors a bit */
.card-header.bg-primary {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
}

.card-header.bg-success {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%) !important;
}

.card-header.bg-info {
    background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%) !important;
}

/* --------- FORM CONTROLS --------- */
.form-label {
    font-size: 0.9rem;
    color: #475569;
}

.form-control-lg,
.form-select-lg {
    font-size: 0.95rem;
    border-radius: 10px;
    padding: 0.55rem 0.9rem;
    border-color: #d1d5db;
}

.form-control:focus,
.form-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.18rem rgba(37, 99, 235, 0.2);
}

/* --------- SELECT2 LOOK --------- */
.select2-container--bootstrap-5 .select2-selection {
    min-height: 48px;
    padding: 6px 10px;
    border-radius: 10px;
    border-color: #d1d5db;
    font-size: 0.95rem;
}

.select2-container--bootstrap-5 .select2-selection__rendered {
    line-height: 1.5;
}

.select2-container--bootstrap-5.select2-container--open .select2-selection {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.18rem rgba(37, 99, 235, 0.18);
}

/* --------- SELECTED BADGES (PANEL + TESTS) --------- */
#selected-items {
    padding-top: 4px;
}

.selected-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    color: white;
    padding: 6px 14px;
    border-radius: 999px;
    margin: 4px 4px 0 0;
    font-size: 0.78rem;
    font-weight: 500;
    box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
    animation: fadeIn-badge 0.25s ease-out;
}

.selected-badge.panel-badge {
    background: linear-gradient(135deg, #f97316, #ec4899);
}

.selected-badge i {
    cursor: pointer;
    margin-left: 4px;
    font-size: 0.85rem;
    opacity: 0.85;
    transition: transform 0.18s ease, opacity 0.18s ease;
}

.selected-badge i:hover {
    transform: scale(1.25);
    opacity: 1;
}

@keyframes fadeIn-badge {
    from { opacity: 0; transform: translateY(4px) scale(0.96); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

/* --------- TABLE STYLING --------- */
.table-responsive {
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.table {
    margin-bottom: 0;
}

.table thead.table-dark {
    background: #0f172a !important;
    border-color: #020617;
}

.table thead th {
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    vertical-align: middle;
}

.table tbody tr {
    transition: background-color 0.15s ease, transform 0.12s ease;
}

.table tbody tr:hover {
    background-color: #f1f5f9;
    transform: translateY(-1px);
}

.table tbody td {
    vertical-align: middle;
    font-size: 0.9rem;
}

/* empty state row */
.table tbody tr td.text-muted {
    font-size: 0.95rem;
}

/* Result inputs */
.result-input {
    font-weight: 600;
    font-size: 0.95rem;
    border-radius: 8px;
}

.result-input:focus {
    transform: scale(1.02);
}

/* Normal / Abnormal indicators */
.result-normal {
    color: #16a34a !important;
    font-weight: 700;
}

.result-abnormal {
    color: #dc2626 !important;
    font-weight: 700;
    animation: blink-soft 1.1s infinite;
}

@keyframes blink-soft {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.65; }
}

/* Drag-and-drop indicators */
.drag-handle {
    cursor: grab;
    color: #9ca3af;
    font-size: 1.1rem;
}

.drag-handle:active {
    cursor: grabbing;
}

.test-row.dragging {
    opacity: 0.55;
    background: #e0f2fe !important;
}

.test-row.drag-over {
    border-top: 3px solid #2563eb !important;
}

/* Remove test icon */
.remove-test-btn {
    cursor: pointer;
    color: #ef4444;
    font-size: 1.1rem;
    transition: transform 0.18s ease, color 0.18s ease;
}

.remove-test-btn:hover {
    transform: scale(1.18);
    color: #b91c1c;
}

/* --------- ALERTS --------- */
.alert {
    border-radius: 10px;
    border: none;
    box-shadow: 0 10px 28px rgba(22, 163, 74, 0.18);
    font-size: 0.9rem;
}

/* --------- BUTTONS --------- */
.btn-lg {
    padding: 0.7rem 1.8rem;
    font-weight: 600;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}

.btn-lg:hover {
    transform: translateY(-1px);
    box-shadow: 0 7px 18px rgba(15, 23, 42, 0.25);
}

.btn-outline-secondary {
    border-width: 1px;
}

.btn-outline-danger {
    border-width: 1px;
}

/* Back button */
.btn.btn-warning.mb-3 {
    border-radius: 999px;
    font-weight: 500;
    padding: 0.45rem 1.2rem;
    box-shadow: 0 6px 14px rgba(234, 179, 8, 0.3);
}

/* --------- RESPONSIVE --------- */
@media (max-width: 992px) {
    .report-container {
        padding: 22px 18px;
        border-radius: 14px;
    }
}

@media (max-width: 768px) {
    body {
        padding: 14px 0;
    }

    .report-container {
        padding: 18px 14px;
        border-radius: 12px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.15);
    }

    .d-flex.justify-content-between.gap-3 {
        flex-direction: column;
    }

    .d-flex.gap-2 {
        width: 100%;
        justify-content: flex-end;
    }

    .btn-lg {
        width: 100%;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(function() {
    const genderSelect = $('select[name="gender"]');
    const testList = $('#test-list');
    const categoryFilter = $('#category_filter');
    const panelSelector = $('#panel_selector');
    const testSelector = $('#test_selector');

    let selectedTests = new Map(); // Store unique tests by ID
    let selectedPanel = null;

    // Store original options
    let originalPanelOptions = panelSelector.html();
    let originalTestOptions = testSelector.html();

    // Initialize Select2 for test selector
    testSelector.select2({
        theme: 'bootstrap-5',
        placeholder: '🔍 Search and select multiple tests...',
        allowClear: true,
        width: '100%'
    });

    // Initialize Select2 for panel selector
    panelSelector.select2({
        theme: 'bootstrap-5',
        placeholder: '-- No Panel Selected --',
        allowClear: true,
        width: '100%'
    });

    // Category filter logic
    categoryFilter.on('change', function() {
        const selectedCategory = $(this).val();

        // Clear current selections if category changes
        panelSelector.val(null);
        testSelector.val(null);
        selectedPanel = null;

        // Restore original options
        let $tempPanel = $('<select>').html(originalPanelOptions);
        let $tempTest = $('<select>').html(originalTestOptions);

        // Filter and rebuild panel options
        let panelOptions = '<option value="">-- No Panel Selected --</option>';
        $tempPanel.find('option').each(function() {
            if ($(this).val() === '') return;

            const categoryId = $(this).data('category-id');
            const categoryIdStr = String(categoryId);
            const selectedCategoryStr = String(selectedCategory);

            if (selectedCategory === '' || categoryIdStr === selectedCategoryStr) {
                panelOptions += this.outerHTML;
            }
        });

        // Filter and rebuild test options
        let testOptions = '';
        $tempTest.find('option').each(function() {
            const categoryId = $(this).data('category-id');
            const categoryIdStr = String(categoryId);
            const selectedCategoryStr = String(selectedCategory);

            if (selectedCategory === '' || categoryIdStr === selectedCategoryStr) {
                testOptions += this.outerHTML;
            }
        });

        // Destroy existing Select2 instances
        if (panelSelector.hasClass("select2-hidden-accessible")) {
            panelSelector.select2('destroy');
        }
        if (testSelector.hasClass("select2-hidden-accessible")) {
            testSelector.select2('destroy');
        }

        // Update HTML
        panelSelector.html(panelOptions);
        testSelector.html(testOptions);

        // Reinitialize Select2
        panelSelector.select2({
            theme: 'bootstrap-5',
            placeholder: '-- No Panel Selected --',
            allowClear: true,
            width: '100%'
        });

        testSelector.select2({
            theme: 'bootstrap-5',
            placeholder: '🔍 Search and select multiple tests...',
            allowClear: true,
            width: '100%'
        });

        // Update badges and test list
        updateSelectedBadges();
        renderAllTests();
    });

    // Handle panel selection
    panelSelector.on('change', function() {
        const panelValue = $(this).val();

        if (panelValue) {
            selectedPanel = panelValue;
            // Clear individual test selections when panel is selected
            testSelector.val(null).trigger('change');
        } else {
            selectedPanel = null;
        }

        updateSelectedBadges();
        loadAllSelectedTests();
    });

    // Handle individual test selection
    testSelector.on('change', function() {
        updateSelectedBadges();
        loadAllSelectedTests();
    });

    // Display selected items as badges
    function updateSelectedBadges() {
        let badgesHtml = '';

        // Show panel badge if selected
        if (selectedPanel) {
            let text = panelSelector.find('option:selected').text();
            badgesHtml += `
                <span class="selected-badge panel-badge">
                    📋 ${text}
                    <i class="bi bi-x-circle" onclick="removePanel()"></i>
                </span>
            `;
        }

        // Show individual test badges
        const selectedTestValues = testSelector.val() || [];
        selectedTestValues.forEach(val => {
            let text = testSelector.find('option[value="' + val + '"]').text();
            badgesHtml += `
                <span class="selected-badge">
                    🧪 ${text}
                    <i class="bi bi-x-circle" onclick="removeTest('${val}')"></i>
                </span>
            `;
        });

        $('#selected-items').html(badgesHtml || '<p class="text-muted mb-0"><i class="bi bi-info-circle"></i> No items selected</p>');
    }

    // Remove panel selection
    window.removePanel = function() {
        selectedPanel = null;
        panelSelector.val(null).trigger('change');
    };

    // Remove individual test
    window.removeTest = function(value) {
        let currentVals = testSelector.val() || [];
        currentVals = currentVals.filter(v => v !== value);
        testSelector.val(currentVals).trigger('change');
    };

    // Remove test from the table
    window.removeTestFromTable = function(testId) {
        if (confirm('Remove this test from the report?')) {
            selectedTests.delete(parseInt(testId));
            renderAllTests();
        }
    };

    // Load all selected tests
    function loadAllSelectedTests() {
        const selectedTestValues = testSelector.val() || [];

        if (!selectedPanel && selectedTestValues.length === 0) {
            selectedTests.clear();
            renderAllTests();
            return;
        }

        testList.html('<tr><td colspan="6" class="text-center"><div class="spinner-border text-primary" role="status"></div> Loading tests...</td></tr>');

        let promises = [];

        // Load panel tests if panel is selected
        if (selectedPanel) {
            let panelId = selectedPanel.replace('panel_', '');
            promises.push($.get(`/reports/panel-tests/${panelId}`));
            $('#test_panel_id').val(panelId);
        } else {
            $('#test_panel_id').val('');
        }

        // Load individual tests
        selectedTestValues.forEach(val => {
            let testId = val.replace('test_', '');
            promises.push($.get(`/reports/test/${testId}`).then(test => [test]));
        });

        Promise.all(promises).then(results => {
            selectedTests.clear();
            results.forEach(tests => {
                if (Array.isArray(tests)) {
                    tests.forEach(test => {
                        if (test && test.id) {
                            selectedTests.set(test.id, test);
                        }
                    });
                }
            });
            renderAllTests();
        }).catch(() => {
            testList.html('<tr><td colspan="6" class="text-center text-danger"><i class="bi bi-exclamation-triangle"></i> Error loading tests</td></tr>');
        });
    }

    // Render all tests with drag and drop
    function renderAllTests() {
        if (selectedTests.size === 0) {
            testList.html('<tr><td colspan="6" class="text-center text-muted py-5"><i class="bi bi-search fs-1 d-block mb-2"></i>Select test panels or tests to begin</td></tr>');
            return;
        }

        let gender = genderSelect.val() || 'Male';
        let rows = '';
        let index = 0;

        selectedTests.forEach((test) => {
            let refRange = gender === 'Male' ? test.reference_range_male
                        : gender === 'Female' ? test.reference_range_female
                        : test.reference_range_other;

            rows += `
                <tr class="test-row" draggable="true" data-test-id="${test.id}">
                    <td class="text-center">
                        <i class="bi bi-grip-vertical drag-handle"></i>
                    </td>
                    <td class="fw-semibold">
                        <i class="bi bi-clipboard-pulse text-primary me-2"></i>${test.name}
                        <input type="hidden" name="tests[${index}][test_id]" value="${test.id}">
                        <input type="hidden" name="tests[${index}][test_name]" value="${test.name}">
                        <input type="hidden" name="tests[${index}][unit]" value="${test.unit}">
                        <input type="hidden" name="tests[${index}][reference_range]"
                            value="${refRange}"
                            data-male="${test.reference_range_male}"
                            data-female="${test.reference_range_female}"
                            data-other="${test.reference_range_other}">
                    </td>
                    <td>
                        <input type="text" name="tests[${index}][value]"
                               class="form-control result-input"
                               placeholder="Enter result">
                    </td>
                    <td class="text-muted">${test.unit}</td>
                    <td class="ref-range"><span class="badge bg-secondary">${refRange}</span></td>
                    <td class="text-center">
                        <i class="bi bi-x-circle remove-test-btn" onclick="removeTestFromTable(${test.id})" title="Remove test"></i>
                    </td>
                </tr>
            `;
            index++;
        });

        testList.html(rows);
        initializeDragAndDrop();
    }

    // Drag and Drop functionality
    function initializeDragAndDrop() {
        const rows = document.querySelectorAll('#test-list .test-row');
        let draggedElement = null;

        rows.forEach(row => {
            row.addEventListener('dragstart', function(e) {
                draggedElement = this;
                this.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
            });

            row.addEventListener('dragend', function() {
                this.classList.remove('dragging');
                rows.forEach(r => r.classList.remove('drag-over'));
                updateTestOrder();
            });

            row.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';

                if (draggedElement !== this) {
                    this.classList.add('drag-over');
                }
            });

            row.addEventListener('dragleave', function() {
                this.classList.remove('drag-over');
            });

            row.addEventListener('drop', function(e) {
                e.preventDefault();

                if (draggedElement !== this) {
                    const allRows = Array.from(rows);
                    const draggedIndex = allRows.indexOf(draggedElement);
                    const targetIndex = allRows.indexOf(this);

                    if (draggedIndex < targetIndex) {
                        this.parentNode.insertBefore(draggedElement, this.nextSibling);
                    } else {
                        this.parentNode.insertBefore(draggedElement, this);
                    }
                }

                this.classList.remove('drag-over');
            });
        });
    }

    // Update test order after drag and drop
    function updateTestOrder() {
        const rows = document.querySelectorAll('#test-list .test-row');
        const newTestsMap = new Map();

        rows.forEach((row, index) => {
            const testId = parseInt(row.dataset.testId);
            const test = selectedTests.get(testId);
            if (test) {
                newTestsMap.set(testId, test);
            }

            // Update input names
            row.querySelectorAll('input[name^="tests["]').forEach(input => {
                const nameMatch = input.name.match(/tests\[\d+\](\[.+\])/);
                if (nameMatch) {
                    input.name = `tests[${index}]${nameMatch[1]}`;
                }
            });
        });

        selectedTests = newTestsMap;
    }

    // Validate and color result
    $(document).on('input', '.result-input', function() {
        let value = parseFloat($(this).val());
        let refRange = $(this).closest('tr').find('.ref-range').text().trim();
        let $input = $(this);

        $input.removeClass('result-normal result-abnormal');

        let parts = refRange.split('-').map(p => parseFloat(p.trim()));
        if (parts.length === 2 && !isNaN(value)) {
            let [min, max] = parts;
            if (value < min || value > max) {
                $input.addClass('result-abnormal');
            } else {
                $input.addClass('result-normal');
            }
        }
    });

    // Update reference range when gender changes
    genderSelect.on('change', function() {
        let gender = $(this).val();
        $('#test-list tr').each(function() {
            let refInput = $(this).find('input[name$="[reference_range]"]');
            if (!refInput.length) return;

            let refRange = gender === 'Male' ? refInput.data('male')
                          : gender === 'Female' ? refInput.data('female')
                          : refInput.data('other');

            $(this).find('td.ref-range').html(`<span class="badge bg-secondary">${refRange}</span>`);
            refInput.val(refRange);
        });
    });

    // Clear all
    $('#clearBtn').on('click', function() {
        if (confirm('Are you sure you want to clear all data?')) {
            $('#reportForm')[0].reset();
            categoryFilter.val('').trigger('change');
            panelSelector.val(null).trigger('change');
            testSelector.val(null).trigger('change');
            selectedPanel = null;
            selectedTests.clear();
            renderAllTests();
        }
    });

    // Prevent double submission
    $('#reportForm').on('submit', function(e) {
        const $btn = $(this).find('button[type="submit"]');
        if ($btn.prop('disabled')) {
            e.preventDefault();
            return false;
        }
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
    });

    // Reset after success
    <?php if(session('success')): ?>
        $('#reportForm')[0].reset();
        categoryFilter.val('').trigger('change');
        panelSelector.val(null).trigger('change');
        testSelector.val(null).trigger('change');
        selectedPanel = null;
        selectedTests.clear();
        renderAllTests();
        $('html, body').animate({ scrollTop: 0 }, 'smooth');
    <?php endif; ?>

    // Set today's date as default
    $('input[name="test_date"]').val(new Date().toISOString().split('T')[0]);
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\resources\views/reports/create.blade.php ENDPATH**/ ?>