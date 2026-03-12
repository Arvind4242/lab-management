<x-filament-panels::page>

<div class="max-w-7xl mx-auto space-y-6">

<div class="flex items-center gap-3">
<div class="text-2xl">🧾</div>
<h2 class="text-xl font-bold">Create Lab Report</h2>
</div>

<form action="{{ route('reports.store') }}" method="POST">
@csrf


<x-filament::section>
<x-slot name="heading">Patient Details</x-slot>

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
</x-filament::section>


<x-filament::section>
<x-slot name="heading">Select Test Category / Panel / Tests</x-slot>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

<div>
<label class="fi-label">Filter by Category</label>

<select id="category_filter" class="fi-input w-full">
<option value="">All Categories</option>

@foreach($categories as $category)
<option value="{{ $category->id }}">
{{ $category->name }}
</option>
@endforeach

</select>

</div>


<div>
<label class="fi-label">Select Test Panel</label>

<select id="panel_selector" class="fi-input w-full">

<option value="">-- No Panel Selected --</option>

@foreach($panels as $id => $name)

<option value="{{ $id }}"
data-category="{{ $panelCategories[$id] ?? '' }}">
{{ $name }}
</option>

@endforeach

</select>

</div>


<div>
<label class="fi-label">Select Individual Tests</label>

<select id="test_selector" class="fi-input w-full">

<option value="">Select Test</option>

@foreach($tests as $test)

<option value="{{ $test->id }}"
data-category="{{ $test->category_id }}">
{{ $test->name }}
</option>

@endforeach

</select>

</div>

</div>

</x-filament::section>



<x-filament::section>
<x-slot name="heading">Test Results</x-slot>

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

</x-filament::section>



<div class="flex justify-between">

<x-filament::button color="gray" tag="a"
href="{{ \App\Filament\Admin\Resources\ReportResource::getUrl() }}">
Cancel
</x-filament::button>

<x-filament::button type="submit" color="success">
Save Report
</x-filament::button>

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

</x-filament-panels::page>
