@extends('layouts.app')

@section('content')
<a href="http://127.0.0.1:8000/admin/reports" class="btn btn-warning">↩️ Back</a>

<div class="container my-4">
    <div class="report-container">
        <h4 class="mb-4">🧾 Create Lab Report</h4>

        {{-- ✅ Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('reports.store') }}" method="POST" id="reportForm">
            @csrf

            <!-- Patient Info -->
            <div class="section-title">Patient Details</div>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Patient Name</label>
                    <input type="text" name="patient_name" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Age</label>
                    <input type="number" name="age" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Referred By</label>
                    <input type="text" name="referred_by" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Client Name</label>
                    <input type="text" name="client_name" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Test Date</label>
                    <input type="date" name="test_date" class="form-control" required>
                </div>
            </div>

            <!-- Test Panel -->
            <div class="section-title">Select Test Panel</div>
            <div class="mb-3">
                <select id="test_selector" class="form-select">
                    <option value="">-- Select Test Panel or Single Test --</option>
                    <optgroup label="Test Panels">
                        @foreach ($panels as $id => $name)
                            <option value="panel_{{ $id }}">{{ $name }} (Panel)</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Individual Tests">
                        @foreach ($tests as $test)
                            <option value="test_{{ $test->id }}">{{ $test->name }}</option>
                        @endforeach
                    </optgroup>
                </select>
                <input type="hidden" name="test_panel_id" id="test_panel_id">
            </div>

            <!-- Test Table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Test</th>
                            <th>Value</th>
                            <th>Unit</th>
                            <th>Reference Range</th>
                        </tr>
                    </thead>
                    <tbody id="test-list">
                        <tr>
                            <td colspan="4" class="text-center text-muted">Select a test panel to load tests.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-start gap-3 btn-group-custom">
                <button type="submit" class="btn btn-success">💾 Save</button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
body { background-color: #f8f9fa; }
.report-container { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 0 6px rgba(0,0,0,0.1); }
.section-title { background: #e9ecef; padding: 8px 12px; font-weight: 600; border-radius: 5px; margin-bottom: 15px; }
table th { background: #f1f3f4; text-transform: uppercase; font-size: 13px; letter-spacing: 0.5px; }
table td input { height: 32px; padding: 5px 8px; }
.btn-group-custom button { min-width: 120px; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    const genderSelect = $('select[name="gender"]');
    const testList = $('#test-list');

    // Handle dropdown change
    $('#test_selector').on('change', function() {
        let selected = $(this).val();
        $('#test_panel_id').val(''); // reset
        testList.html('<tr><td colspan="4" class="text-center text-muted">Loading...</td></tr>');

        if (!selected) {
            testList.html('<tr><td colspan="4" class="text-center text-muted">Select a test panel or test.</td></tr>');
            return;
        }

        if (selected.startsWith('panel_')) {
            let panelId = selected.replace('panel_', '');
            $('#test_panel_id').val(panelId);

            $.get(`/reports/panel-tests/${panelId}`, function(tests) {
                renderTests(tests);
            }).fail(() => {
                testList.html('<tr><td colspan="4" class="text-center text-danger">Error loading panel tests.</td></tr>');
            });

        } else if (selected.startsWith('test_')) {
            let testId = selected.replace('test_', '');
            $.get(`/reports/test/${testId}`, function(test) {
                if (!test || !test.id) {
                    testList.html('<tr><td colspan="4" class="text-center text-danger">Test not found.</td></tr>');
                    return;
                }
                renderTests([test]);
            }).fail(() => {
                testList.html('<tr><td colspan="4" class="text-center text-danger">Error loading test.</td></tr>');
            });
        }
    });

    // Render tests in table
    function renderTests(tests) {
        if (!tests || tests.length === 0) {
            testList.html('<tr><td colspan="4" class="text-center text-danger">No tests found.</td></tr>');
            return;
        }

        let gender = genderSelect.val() || 'Male';
        let rows = '';

        tests.forEach((test, index) => {
            let refRange = gender === 'Male' ? test.reference_range_male
                        : gender === 'Female' ? test.reference_range_female
                        : test.reference_range_other;

            rows += `
                <tr>
                    <td>${test.name}
                        <input type="hidden" name="tests[${index}][test_id]" value="${test.id}">
                        <input type="hidden" name="tests[${index}][test_name]" value="${test.name}">
                        <input type="hidden" name="tests[${index}][unit]" value="${test.unit}">
                        <input type="hidden" name="tests[${index}][reference_range]"
                            value="${refRange}"
                            data-male="${test.reference_range_male}"
                            data-female="${test.reference_range_female}"
                            data-other="${test.reference_range_other}">
                    </td>
                    <td><input type="text" name="tests[${index}][value]" class="form-control result-input" placeholder="Enter result"></td>
                    <td>${test.unit}</td>
                    <td class="ref-range">${refRange}</td>
                </tr>
            `;
        });

        testList.html(rows);
    }

    // Color result based on range
    $(document).on('input', '.result-input', function() {
        let value = parseFloat($(this).val());
        let refRange = $(this).closest('tr').find('.ref-range').text().trim();
        let $input = $(this);
        $input.css('color', ''); // reset

        let parts = refRange.split('-').map(p => parseFloat(p.trim()));
        if (parts.length === 2 && !isNaN(value)) {
            let [min, max] = parts;
            if (value < min || value > max) {
                $input.css('color', 'red');
            } else {
                $input.css('color', 'green');
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
            $(this).find('td.ref-range').text(refRange);
            refInput.val(refRange);
        });
    });

    // Disable save button after submit to prevent duplicates
    $('#reportForm').on('submit', function() {
        const $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).text('Saving...');
    });

    // ✅ Reset form and scroll to message after success
    @if(session('success'))
        $('#reportForm')[0].reset();
        $('#test-list').html('<tr><td colspan="4" class="text-center text-muted">Select a test panel to load tests.</td></tr>');
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    @endif
});
</script>
@endpush

@endsection
