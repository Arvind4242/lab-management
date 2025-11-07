@extends('layouts.app')

@section('content')
<a href="http://127.0.0.1:8000/admin/reports" class="btn btn-warning">↩️ Back</a>
<div class="container my-4">
    <div class="report-container">
        <h4 class="mb-4">✏️ Edit Lab Report</h4>

        <form action="{{ route('reports.update', $report->id) }}" method="POST" id="reportForm">
            @csrf
            @method('PUT')

            <!-- Patient Info -->
            <div class="section-title">Patient Details</div>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Patient Name</label>
                    <input type="text" name="patient_name" class="form-control" value="{{ $report->patient_name }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Age</label>
                    <input type="number" name="age" class="form-control" value="{{ $report->age }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="">Select</option>
                        <option value="Male" {{ $report->gender === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $report->gender === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ $report->gender === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Referred By</label>
                    <input type="text" name="referred_by" class="form-control" value="{{ $report->referred_by }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Client Name</label>
                    <input type="text" name="client_name" class="form-control" value="{{ $report->client_name }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Test Date</label>
                   <input type="date" name="test_date" class="form-control" value="{{ $report->test_date->format('Y-m-d') }}" required>
                </div>
            </div>

            <!-- Test Panel -->
            <div class="section-title">Select Test Panel</div>
            <div class="mb-3">
                <select id="test_panel" class="form-select">
                    <option value="">-- Select Panel --</option>
                    @foreach ($panels as $id => $name)
                        <option value="{{ $id }}" {{ $report->test_panel_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="test_panel_id" id="test_panel_id" value="{{ $report->test_panel_id }}">

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
                        @foreach($report->report_results as $index => $result)
                            <tr>
                                <td>{{ $result->test_name }}
                                    <input type="hidden" name="tests[{{ $index }}][test_id]" value="{{ $result->report_test_id }}">
                                    <input type="hidden" name="tests[{{ $index }}][test_name]" value="{{ $result->test_name }}">
                                    <input type="hidden" name="tests[{{ $index }}][unit]" value="{{ $result->unit }}">
                                    <input type="hidden" name="tests[{{ $index }}][reference_range]" value="{{ $result->reference_range }}"
                                           data-male="{{ $result->test->reference_range_male ?? '' }}"
                                           data-female="{{ $result->test->reference_range_female ?? '' }}"
                                           data-other="{{ $result->test->reference_range_other ?? '' }}">
                                </td>
                                <td><input type="text" name="tests[{{ $index }}][value]" class="form-control" value="{{ $result->value }}"></td>
                                <td>{{ $result->unit }}</td>
                                <td class="ref-range">{{ $result->reference_range }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-start gap-3 btn-group-custom mb-3">
                {{-- <a href="{{ url()->previous() }}" class="btn btn-warning">↩️ Return</a> --}}
                <button type="submit" class="btn btn-success">💾 Update</button>
                <button type="button" class="btn btn-primary" id="previewBtn">👁️ Preview</button>
                <button type="button" class="btn btn-secondary" id="printBtn">🖨️ Print</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
$(function() {
    const genderSelect = $('select[name="gender"]');

    function updateReferenceRanges() {
        const gender = genderSelect.val();

        $('#test-list tr').each(function() {
            const refInput = $(this).find('input[name$="[reference_range]"]');
            const refCell = $(this).find('td.ref-range');

            if (!refInput.length || !refCell.length) return;

            const maleRange = refInput.attr('data-male') ?? '';
            const femaleRange = refInput.attr('data-female') ?? '';
            const otherRange = refInput.attr('data-other') ?? '';

            let refRange = '';
            if (gender === 'Male') refRange = maleRange;
            else if (gender === 'Female') refRange = femaleRange;
            else refRange = otherRange;

            refCell.text(refRange);
            refInput.val(refRange);
        });
    }

    // Initial update
    updateReferenceRanges();

    // On gender change
    genderSelect.on('change', updateReferenceRanges);

    // Sync panel id
    $('#reportForm').on('submit', function() {
        $('#test_panel_id').val($('#test_panel').val());
    });
});
</script>
@endpush
@endsection
