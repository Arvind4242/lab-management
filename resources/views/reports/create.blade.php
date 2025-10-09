@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="report-container">
        <h4 class="mb-4">🧾 Create Lab Report</h4>

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
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
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
                <select id="test_panel" class="form-select">
                    <option value="">-- Select Panel --</option>
                    @foreach ($panels as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Hidden input to save selected panel -->
            <input type="hidden" name="test_panel_id" id="test_panel_id">

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
                <button type="button" class="btn btn-primary" id="previewBtn">👁️ Preview</button>
                <button type="button" class="btn btn-secondary" id="printBtn">🖨️ Print</button>
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
    // Load tests when panel changes
    $('#test_panel').on('change', function() {
        let panelId = $(this).val();
        $('#test_panel_id').val(panelId); // save selected panel

        if (!panelId) {
            $('#test-list').html('<tr><td colspan="4" class="text-center text-muted">Select a test panel to load tests.</td></tr>');
            return;
        }

        $.get(`/reports/panel-tests/${panelId}`, function(tests) {
            if (!tests || tests.length === 0) {
                $('#test-list').html('<tr><td colspan="4" class="text-center text-danger">No tests found for this panel.</td></tr>');
                return;
            }

            let rows = '';
            tests.forEach((test, index) => {
                rows += `
                    <tr>
                        <td>${test.name}
                            <input type="hidden" name="tests[${index}][test_id]" value="${test.id}">
                            <input type="hidden" name="tests[${index}][test_name]" value="${test.name}">
                            <input type="hidden" name="tests[${index}][unit]" value="${test.unit}">
                            <input type="hidden" name="tests[${index}][reference_range]" value="${test.reference_range}">
                        </td>
                        <td><input type="text" name="tests[${index}][value]" class="form-control" placeholder="Enter result"></td>
                        <td>${test.unit}</td>
                        <td>${test.reference_range}</td>
                    </tr>
                `;
            });

            $('#test-list').html(rows);
        }).fail(function() {
            $('#test-list').html('<tr><td colspan="4" class="text-center text-danger">Error loading tests.</td></tr>');
        });
    });

    // Preview
    $('#previewBtn').click(function() {
        alert('Preview feature will show report layout before final print.');
    });

    // Print
    $('#printBtn').click(function() {
        window.print();
    });

    // Ensure hidden field is set on submit
    $('#reportForm').on('submit', function() {
        $('#test_panel_id').val($('#test_panel').val());
    });
});
</script>
@endpush
@endsection
