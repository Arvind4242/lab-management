@extends('layouts.app')

@section('content')
<a href="http://127.0.0.1:8000/admin/reports" class="btn btn-warning mb-3">
    <i class="bi bi-arrow-left"></i> Back
</a>

<div class="container my-4">
    <div class="report-container">
        <div class="d-flex align-items-center mb-4">
            <div class="report-icon">🧾</div>
            <h4 class="mb-0 ms-3">Create Lab Report</h4>
        </div>

        {{-- ✅ Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('reports.store') }}" method="POST" id="reportForm">
            @csrf

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
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Test Panel Selection (Single) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">📋 Select Test Panel (Optional - One Only)</label>
                        <select id="panel_selector" class="form-select form-select-lg">
                            <option value="">-- No Panel Selected --</option>
                            @foreach ($panels as $id => $name)
                                <option value="panel_{{ $id }}" data-category-id="{{ $panelCategories[$id] ?? '' }}">
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted mt-2 d-block">
                            <i class="bi bi-info-circle"></i> Select one panel or choose individual tests below
                        </small>
                    </div>

                    <!-- Individual Tests Selection (Multiple) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">🧪 Select Individual Tests (Multiple)</label>
                        <select id="test_selector" class="form-select form-select-lg" multiple>
                            @foreach ($tests as $test)
                                <option value="test_{{ $test->id }}" data-category-id="{{ $test->category_id ?? '' }}">
                                    {{ $test->name }}
                                </option>
                            @endforeach
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
                <div class="card-header bg-info bg-gradient text-white">
                    <i class="bi bi-table me-2"></i>Test Results
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th width="30%">Test Name</th>
                                    <th width="25%">Result Value</th>
                                    <th width="15%">Unit</th>
                                    <th width="30%">Reference Range</th>
                                </tr>
                            </thead>
                            <tbody id="test-list">
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px 0;
}

.report-container {
    background: #fff;
    padding: 35px;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

.report-icon {
    font-size: 48px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.table thead th {
    font-weight: 600;
    font-size: 13px;
    letter-spacing: 0.5px;
    border: none;
}

.table tbody tr {
    transition: background-color 0.2s;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.result-input {
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s;
}

.result-input:focus {
    transform: scale(1.02);
}

.result-normal { color: #28a745 !important; font-weight: bold; }
.result-abnormal { color: #dc3545 !important; font-weight: bold; animation: blink 1s infinite; }

@keyframes blink {
    0%, 50%, 100% { opacity: 1; }
    25%, 75% { opacity: 0.7; }
}

.btn-lg {
    padding: 12px 30px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s;
}

.btn-lg:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.select2-container--bootstrap-5 .select2-selection {
    min-height: 48px;
    padding: 8px;
    font-size: 16px;
}

.selected-badge {
    display: inline-block;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 8px 15px;
    border-radius: 25px;
    margin: 5px;
    font-size: 14px;
    animation: fadeIn 0.3s;
}

.selected-badge.panel-badge {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.selected-badge i {
    cursor: pointer;
    margin-left: 8px;
    transition: transform 0.2s;
}

.selected-badge i:hover {
    transform: scale(1.3);
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.8); }
    to { opacity: 1; transform: scale(1); }
}

.alert {
    border-radius: 10px;
    border: none;
}
</style>
@endpush

@push('scripts')
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

    // Load all selected tests
    function loadAllSelectedTests() {
        const selectedTestValues = testSelector.val() || [];

        if (!selectedPanel && selectedTestValues.length === 0) {
            selectedTests.clear();
            renderAllTests();
            return;
        }

        testList.html('<tr><td colspan="4" class="text-center"><div class="spinner-border text-primary" role="status"></div> Loading tests...</td></tr>');

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
            testList.html('<tr><td colspan="4" class="text-center text-danger"><i class="bi bi-exclamation-triangle"></i> Error loading tests</td></tr>');
        });
    }

    // Render all tests
    function renderAllTests() {
        if (selectedTests.size === 0) {
            testList.html('<tr><td colspan="4" class="text-center text-muted py-5"><i class="bi bi-search fs-1 d-block mb-2"></i>Select test panels or tests to begin</td></tr>');
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
                <tr data-test-id="${test.id}">
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
                </tr>
            `;
            index++;
        });

        testList.html(rows);
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
    @if(session('success'))
        $('#reportForm')[0].reset();
        categoryFilter.val('').trigger('change');
        panelSelector.val(null).trigger('change');
        testSelector.val(null).trigger('change');
        selectedPanel = null;
        selectedTests.clear();
        renderAllTests();
        $('html, body').animate({ scrollTop: 0 }, 'smooth');
    @endif

    // Set today's date as default
    $('input[name="test_date"]').val(new Date().toISOString().split('T')[0]);
});
</script>
@endpush

@endsection
