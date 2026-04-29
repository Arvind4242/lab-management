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

<div class="max-w-7xl mx-auto space-y-6">

<div class="flex items-center gap-3">
<div class="text-2xl">🧾</div>
<h2 class="text-xl font-bold">Create Lab Report</h2>
</div>

<form action="<?php echo e(route('reports.store')); ?>" method="POST">
<?php echo csrf_field(); ?>


<?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
 <?php $__env->slot('heading', null, []); ?> Patient Details <?php $__env->endSlot(); ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

<div>
<label class="fi-label">Patient Name *</label>
<input type="text" name="patient_name" required class="fi-input w-full">
</div>

<div>
<label class="fi-label">Age *</label>
<input type="number" name="age" required class="fi-input w-full">
</div>

<div>
<label class="fi-label">Gender *</label>
<select name="gender" id="gender" required class="fi-input w-full">
<option value="">Select</option>
<option value="Male">Male</option>
<option value="Female">Female</option>
<option value="Other">Other</option>
</select>
</div>

<div>
<label class="fi-label">Referred By</label>
<input type="text" name="referred_by" class="fi-input w-full">
</div>

<div>
<label class="fi-label">Client Name</label>
<input type="text" name="client_name" class="fi-input w-full">
</div>

<div>
<label class="fi-label">Test Date *</label>
<input type="date" name="test_date" required class="fi-input w-full">
</div>

</div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $attributes = $__attributesOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $component = $__componentOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__componentOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>


<?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
 <?php $__env->slot('heading', null, []); ?> Select Test Category / Panel / Tests <?php $__env->endSlot(); ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

<div>
<label class="fi-label">Filter by Category</label>

<select id="category_filter" class="fi-input w-full">
<option value="">All Categories</option>

<!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<option value="<?php echo e($category->id); ?>">
<?php echo e($category->name); ?>

</option>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

</select>

</div>


<div>
<label class="fi-label">Select Test Panel</label>

<select id="panel_selector" class="fi-input w-full">

<option value="">-- No Panel Selected --</option>

<!--[if BLOCK]><![endif]--><?php $__currentLoopData = $panels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<option value="<?php echo e($id); ?>"
data-category="<?php echo e($panelCategories[$id] ?? ''); ?>">
<?php echo e($name); ?>

</option>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

</select>

</div>


<div>
<label class="fi-label">Select Individual Tests</label>

<select id="test_selector" class="fi-input w-full">

<option value="">Select Test</option>

<!--[if BLOCK]><![endif]--><?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<option value="<?php echo e($test->id); ?>"
data-category="<?php echo e($test->category_id); ?>">
<?php echo e($test->name); ?>

</option>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

</select>

</div>

</div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $attributes = $__attributesOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $component = $__componentOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__componentOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>



<?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
 <?php $__env->slot('heading', null, []); ?> Test Results <?php $__env->endSlot(); ?>

<div class="overflow-x-auto">

<table class="w-full text-sm border rounded-lg overflow-hidden">

<thead class="bg-gray-100">

<tr>
<th class="p-3 text-left">Test</th>
<th class="p-3 text-left">Value</th>
<th class="p-3 text-left">Unit</th>
<th class="p-3 text-left">Range</th>
<th class="p-3 text-left">Action</th>
</tr>

</thead>

<tbody id="test-list">

<tr>
<td colspan="5" class="text-center text-gray-400 py-8">
Select tests to begin
</td>
</tr>

</tbody>

</table>

</div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $attributes = $__attributesOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $component = $__componentOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__componentOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>



<div class="flex justify-between">

<?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['color' => 'gray','tag' => 'a','href' => ''.e(\App\Filament\Admin\Resources\ReportResource::getUrl()).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => 'gray','tag' => 'a','href' => ''.e(\App\Filament\Admin\Resources\ReportResource::getUrl()).'']); ?>
Cancel
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['type' => 'submit','color' => 'success']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','color' => 'success']); ?>
Save Report
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>

</div>

</form>

</div>


<script>

let selectedTests = [];


/* CATEGORY FILTER */

document.getElementById("category_filter").addEventListener("change",function(){

let category=this.value;


/* FILTER PANELS */

document.querySelectorAll("#panel_selector option").forEach(option=>{

if(option.value==="") return;

let panelCategory=option.dataset.category;

option.style.display =
(!category || panelCategory===category) ? "block":"none";

});


/* FILTER TESTS */

document.querySelectorAll("#test_selector option").forEach(option=>{

if(option.value==="") return;

let testCategory=option.dataset.category;

option.style.display =
(!category || testCategory===category) ? "block":"none";

});

});


/* PANEL LOAD */

document.getElementById("panel_selector").addEventListener("change",function(){

let panelId=this.value;

if(!panelId) return;

fetch("/reports/panel-tests/"+panelId)

.then(res=>res.json())

.then(data=>{

data.forEach(test=>addTestRow(test));

});

});


/* SINGLE TEST */

document.getElementById("test_selector").addEventListener("change",function(){

let testId=this.value;

if(!testId) return;

fetch("/reports/test/"+testId)

.then(res=>res.json())

.then(test=>{

addTestRow(test);

});

});


/* ADD TEST ROW */

function addTestRow(test){

if(selectedTests.includes(test.id)) return;

selectedTests.push(test.id);

let gender=document.getElementById("gender").value;

let range=getRange(test,gender);

let table=document.getElementById("test-list");

if(table.innerHTML.includes("Select tests")){
table.innerHTML="";
}

table.innerHTML+=`

<tr data-id="${test.id}">

<td>

<input type="hidden" name="tests[${test.id}][test_id]" value="${test.id}">
<input type="hidden" name="tests[${test.id}][test_name]" value="${test.name}">

${test.name}

</td>


<td>

<input type="text"
name="tests[${test.id}][value]"
class="fi-input w-full">

</td>


<td>

<input type="hidden"
name="tests[${test.id}][unit]"
value="${test.unit ?? ''}">

${test.unit ?? ''}

</td>


<td class="reference-range">

<input type="hidden"
name="tests[${test.id}][reference_range]"
value="${range}"
data-male="${test.reference_range_male ?? ''}"
data-female="${test.reference_range_female ?? ''}"
data-other="${test.reference_range_other ?? ''}">

${range}

</td>


<td>

<button type="button"
class="text-red-600 remove-test">
Remove
</button>

</td>

</tr>

`;

}


/* GET RANGE */

function getRange(test,gender){

if(gender==="Male") return test.reference_range_male ?? "";

if(gender==="Female") return test.reference_range_female ?? "";

return test.reference_range_other ?? "";

}


/* REMOVE */

document.addEventListener("click",function(e){

if(e.target.classList.contains("remove-test")){

let row=e.target.closest("tr");

let id=row.dataset.id;

selectedTests=selectedTests.filter(t=>t!=id);

row.remove();

}

});


/* GENDER CHANGE */

document.getElementById("gender").addEventListener("change",function(){

let gender=this.value;

document.querySelectorAll("#test-list tr").forEach(row=>{

let refInput=row.querySelector('input[name$="[reference_range]"]');

let range=
gender==="Male"?refInput.dataset.male:
gender==="Female"?refInput.dataset.female:
refInput.dataset.other;

row.querySelector(".reference-range").innerHTML=`

<input type="hidden"
name="${refInput.name}"
value="${range}"
data-male="${refInput.dataset.male}"
data-female="${refInput.dataset.female}"
data-other="${refInput.dataset.other}">

${range}

`;

});

});

</script>

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
<?php /**PATH C:\lab\lab-management\resources\views/filament/admin/pages/create-report.blade.php ENDPATH**/ ?>