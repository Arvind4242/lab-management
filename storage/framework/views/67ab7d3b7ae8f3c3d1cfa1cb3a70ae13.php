<?php if (isset($component)) { $__componentOriginal166a02a7c5ef5a9331faf66fa665c256 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.page.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament-panels::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>


<a href="<?php echo e(\App\Filament\Admin\Resources\ReportResource::getUrl()); ?>"
   class="btn btn-warning mb-3">
    <i class="bi bi-arrow-left"></i> Back
</a>

<div class="container my-4">
    <div class="report-container">

        <div class="d-flex align-items-center mb-4">
            <div class="report-icon">🧾</div>
            <h4 class="mb-0 ms-3">Create Lab Report</h4>
        </div>

        
        <!--[if BLOCK]><![endif]--><?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <form action="<?php echo e(route('reports.store')); ?>" method="POST" id="reportForm">
            <?php echo csrf_field(); ?>

            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary bg-gradient text-white">
                    <i class="bi bi-person-fill me-2"></i> Patient Details
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Patient Name *</label>
                            <input type="text" name="patient_name" class="form-control form-control-lg" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Age *</label>
                            <input type="number" name="age" class="form-control form-control-lg" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Gender *</label>
                            <select name="gender" class="form-select form-select-lg" required>
                                <option value="">Select</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Referred By</label>
                            <input type="text" name="referred_by" class="form-control form-control-lg">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Client Name</label>
                            <input type="text" name="client_name" class="form-control form-control-lg">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Test Date *</label>
                            <input type="date" name="test_date" class="form-control form-control-lg" required>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success bg-gradient text-white">
                    <i class="bi bi-clipboard2-pulse-fill me-2"></i>
                    Select Test Category, Panel & Tests
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Filter by Category</label>
                        <select id="category_filter" class="form-select form-select-lg">
                            <option value="">All Categories</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Test Panel</label>
                        <select id="panel_selector" class="form-select form-select-lg">
                            <option value="">-- No Panel Selected --</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Individual Tests</label>
                        <select id="test_selector" class="form-select form-select-lg" multiple></select>
                    </div>

                    <input type="hidden" name="test_panel_id" id="test_panel_id">
                    <div id="selected-items" class="mt-3"></div>
                </div>
            </div>

            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info bg-gradient text-white">
                    <i class="bi bi-table me-2"></i> Test Results
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th></th>
                                    <th>Test</th>
                                    <th>Value</th>
                                    <th>Unit</th>
                                    <th>Range</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="test-list">
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        Select tests to begin
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            
            <div class="d-flex justify-content-between gap-3">
                <button type="button"
                        onclick="window.location.href='<?php echo e(\App\Filament\Admin\Resources\ReportResource::getUrl()); ?>'"
                        class="btn btn-outline-secondary btn-lg">
                    Cancel
                </button>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-danger btn-lg" id="clearBtn">
                        Clear All
                    </button>
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        Save Report
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>


<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
.filament-page {
    background: radial-gradient(circle at top left, #e0f2fe, #f3f4f6);
    padding: 24px 0;
}
.report-container {
    background: #fff;
    padding: 28px;
    border-radius: 18px;
    box-shadow: 0 18px 45px rgba(15,23,42,.16);
}
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('scripts'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<?php $__env->stopPush(); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $attributes = $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $component = $__componentOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\laravel\resources\views/filament/admin/pages/create-report.blade.php ENDPATH**/ ?>