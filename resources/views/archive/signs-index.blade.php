// signs/index.blade.php
@extends('layouts.master')
@section('page-header')
<div class="breadcrumb-header justify-content-between">
<div class="my-auto">
<div class="d-flex">
<h4 class="content-title mb-0 my-auto">التوقيعات</h4>
</div>
</div>
</div>  
@endsection
@section('content')
<form action="" method="post" id="selectedGroub">   
@csrf
</form>

<div class="modal fade" id="addModal" tabindex="-1">
<div class="modal-dialog">
<form action="{{ route('sign.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="modal-content">
<div class="modal-header"><h5>إضافة توقيع</h5></div>
<div class="modal-body">

<div class="form-group"><label>اسم التوقيع</label><input type="text" name="name" id="add_name" class="form-control" required></div>


<label>الجواب</label>
<input type="file" name="file" class="filepond" >
</div>     
<div class="modal-footer"><button type="submit" class="btn btn-primary">حفظ</button></div>
</div>
</form>
</div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
<div class="modal-dialog">
<form id="editForm" action="{{route('sign.update')}}" method="POST">
@csrf @method('PUT')
<input type="hidden" name="id" id="edit_id">
<div class="modal-content">
<div class="modal-header"><h5>تعديل توقيع</h5></div>
<div class="modal-body">

<div class="form-group"><label>اسم التوقيع</label><input type="text" name="name" id="edit_name" class="form-control" required></div>

</div>
<div class="modal-footer"><button type="submit" class="btn btn-success">تحديث</button></div>
</div>
</form>
</div>
</div>

<div class="row">
<div class="col-12">
<div class="card">
<div class="card-header bg-white d-flex justify-content-between align-items-center">
<h4 class="mb-0">إدارة التوقيعات</h4>
<div class="bulkActions">
<button class="btn btn-primary add-btn" data-toggle="modal" data-target="#addModal"><i class="fa fa-plus"></i> إضافة</button>
<button class="btn btn-danger del-all-btn" id="bulkDelete"><i class="fa fa-trash"></i> حذف المحدد</button>
<button class="btn btn-warning text-white archive-all-btn" id="bulkArchive"><i class="fa fa-archive"></i> أرشفة المحدد</button>
</div>
</div>   
<div class="card-body">
<input type="text" id="searchInput" class="form-control mb-3" placeholder="بحث سريع عن التوقيعات...">
<div class="table-responsive">
<table class="table table-hover text-center" id="entityTable">
<thead class="thead-light">
<tr>
<th><input type='checkbox' id='selectAll' style="margin-top:10px;"></th>
<th>اسم التوقيع</th>
<th>العمليات</th>
</tr>
</thead>

<tbody id="index">
@forelse ($signs as $item)
<tr id="row-{{ $item->id }}">
<td><input type='checkbox' class="selectItem" name="ids[]" form="selectedGroub" value='{{ $item->id }}'></td>
<td>{{ $item->name }}</td>
<td>
<a class="btn btn-sm btn-success viewBtn" href="{{ route('sign.preview', $item->id) }}"><i class="fa fa-eye"></i></a>
<button class="btn btn-sm btn-info editBtn"
data-toggle="modal"
data-target="#editModal"
data-id="{{ $item->id }}"
data-name="{{ $item->name }}">
<i class="fa fa-edit"></i></button>
<button class="btn btn-sm btn-warning text-white archiveBtn" data-id="{{ $item->id }}"><i class="fa fa-archive"></i></button>
<button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $item->id }}"><i class="fa fa-trash"></i></button>
</td>
</tr>
@empty
<tr><td colspan="20"><p class="no-data">لا توجد بيانات للعرض</p></td></tr>
@endforelse
</tbody>

<tbody id="result"></tbody>
</table>
</div>
</div>
</div>  
</div>
</div>
</div>  
</div>
</div>

@endsection

@section('js')
@if ($errors->any())
<script>
window.addEventListener('load', () => {
setTimeout(() => {
Swal.fire({
icon: 'error',
title: 'حصل خطأ!',
text:  `
@foreach ($errors->all() as $error)
{{ $error }}
@endforeach
`,
confirmButtonText: 'حسناً'
});
}, 500);
});
</script>
@endif

<script>
$(document).on('click', '.editBtn', function() {
let edit_name =  $(this).data('name');
let id =  $(this).data('id');
$('#editModal').on('shown.bs.modal', function () {
$('#edit_name').val(edit_name);
$('#edit_id').val(id);
});
})
</script>     




<script>
$(document).on('click', '.deleteBtn', function(e) {
btn.addEventListener('click', function(e) {
e.preventDefault(); 
let id = btn.dataset.id;
Swal.fire({
title: 'هل متأكد من عمليه الحذف؟',
text: "سوف يتم حذف العنصر نهائياً",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#d33',
cancelButtonColor: '#3085d6',
confirmButtonText: 'نعم',
cancelButtonText: 'رجوع'
}).then((result) => {
if (result.isConfirmed) {
window.location.href = "/sign/destroy/"+id;
}
});
});
});
</script>

<script>
$(document).on('click', '.archiveBtn', function(e) {
btn.addEventListener('click', function(e) {
e.preventDefault(); 
let id = btn.dataset.id;
Swal.fire({
title: 'هل متأكد من عمليه الارشفه',
text: "سوف يتم ارشفه العنصر",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#d33',
cancelButtonColor: '#3085d6',
confirmButtonText: 'نعم',
cancelButtonText: 'رجوع'
}).then((result) => {
if (result.isConfirmed) {
window.location.href = "/sign/archive/"+id;
}
});
});
});
</script>

<script>
const checkAll = document.getElementById('selectAll');
const itemCheckboxes = document.querySelectorAll('.selectItem');
const allBtns = document.querySelectorAll('.bulkActions button');

checkAll.addEventListener('change', function() {
itemCheckboxes.forEach(cb => cb.checked = this.checked);
});

allBtns.forEach(btn => {
btn.addEventListener('click', function(e) {
e.preventDefault();

if (btn.classList.contains('add-btn')) {
return;
}  

const anyChecked = Array.from(itemCheckboxes).some(cb => cb.checked);

if (!anyChecked) {
Swal.fire({
icon: 'info',
title: 'اختار عنصر الأول',
confirmButtonText: 'حسنا'
});
return;
}

if (btn.classList.contains('del-all-btn')) {
Swal.fire({
title: 'هل متأكد من عمليه الحذف؟',
text: "سوف يتم حذف العنصر نهائياً",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#d33',
cancelButtonColor: '#3085d6',
confirmButtonText: 'نعم',
cancelButtonText: 'رجوع'
}).then((result) => {
if (result.isConfirmed) {
let form = document.getElementById('selectedGroub');
form.action = `{{ route('sign.destroy.all') }}`;
form.submit();
}
});
} else if(btn.classList.contains('archive-all-btn')) {
Swal.fire({
title: 'هل متأكد من عمليه الارشفه',
text: "سوف يتم ارشفه العنصر",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#d33',
cancelButtonColor: '#3085d6',
confirmButtonText: 'نعم',
cancelButtonText: 'رجوع'
}).then((result) => {
if (result.isConfirmed) {
let form = document.getElementById('selectedGroub');
form.action = `{{ route('sign.archive.all') }}`;
form.submit();
}
});
}  
});
});
</script>

<script>
$(document).ready(function() {
$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});

$('#searchInput').on('keyup', function() {
let search = $(this).val();

if (search == '') {
$('#result').hide();
$('#index').show();
return;
}

$('#result').show();
$('#index').hide();

$.ajax({
url: '/sign/search',
type: 'post',
data: { search: search },
success: function(data) {
let html = '';
if (!data || data.length === 0) {
html = `<tr><td colspan="20"><p class="no-data">لا توجد نتائج للبحث</p></td></tr>`;
} else {   
$.each(data, function(i, item) {
let previewUrl = '/sign/preview/'+item.id;
html += `
<tr id="row-${item.id}">
<td><input type='checkbox' class="selectItem" name="ids[]" value='${item.id}'></td>
<td>${item.name}</td>  
<td>${item.user?.name ?? ''}</td>
<td>
<a class="btn btn-sm btn-success viewBtn" href="${previewUrl}"><i class="fa fa-eye"></i></a>
<button class="btn btn-sm btn-info editBtn"
data-toggle="modal"
data-target="#editModal"
data-id="${item.id}"
data-name="${item.name}">
<i class="fa fa-edit"></i></button>
<button class="btn btn-sm btn-warning text-white archiveBtn" data-id="${item.id}"><i class="fa fa-archive"></i></button>
<button class="btn btn-sm btn-danger deleteBtn" data-id="${item.id}"><i class="fa fa-trash"></i></button>
</td>
</tr>`;
});
}
$('#result').html(html);
}
});
});
});
</script>
@endsection