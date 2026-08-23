@extends('layouts.master')
@section('page-header')
<div class="breadcrumb-header justify-content-between">
<div class="my-auto">
<div class="d-flex">
<h4 class="content-title mb-0 my-auto">الجوابات</h4>
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
<form action="{{ route('mail.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="modal-content">
<div class="modal-header"><h5>إضافة الجواب</h5></div>
<div class="modal-body">

<div class="form-group"><label>العنوان</label><input type="text" name="title" id="add_title" class="form-control" required></div>
<div class="form-group"><label>الوصف</label><textarea name="description" id="add_description" class="form-control"></textarea></div>

<div class="form-group"><label>الجهه المصدره</label>
<select name="entity_id" id="add_entity_id" class="form-control mySelect">
@foreach($entities ?? [] as $rel)
<option value="{{ $rel->id }}">{{ $rel->name }}</option>
@endforeach
</select>
</div>



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
<form id="editForm" action="{{route('mail.update')}}" method="POST">
@csrf @method('PUT')
<input type="hidden" name="id" id="edit_id">
<div class="modal-content">
<div class="modal-header"><h5>تعديل الجواب</h5></div>
<div class="modal-body">

<div class="form-group"><label>العنوان</label><input type="text" name="title" id="edit_title" class="form-control" required></div>
<div class="form-group"><label>الوصف</label><textarea name="description" id="edit_description" class="form-control"></textarea></div>

<div class="form-group"><label>الجهه</label>
<select name="entity_id" id="edit_entity_id" class="form-control mySelect">
@foreach($entities ?? [] as $rel)
<option value="{{ $rel->id }}">{{ $rel->name }}</option>
@endforeach
</select>
</div>


</div>
<div class="modal-footer"><button type="submit" class="btn btn-success">تحديث</button></div>
</div>
</form>
</div>
</div>



<div class="modal fade" id="shareModal" tabindex="-1">
<div class="modal-dialog">
<form id="shareForm" action="{{route('mail.share')}}" method="POST">
@csrf @method('PUT')
<input type="hidden" name="id" id="share_id">
<div class="modal-content">
<div class="modal-header"><h5>نشر الجواب</h5></div>
<div class="modal-body">

<div class="form-group"><label>الاداره</label>
<select name="departments[]" id="share_department" multiple="multiple" class="mySelectMultiple">
@foreach($departments ?? [] as $rel)
<option value="{{ $rel->id }}">{{ $rel->name }}</option>
@endforeach
</select>
</div>


  <div class="form-group"><label>الخصوصيه</label>
<select name="privacy" id="share_privacy" class="form-control">
@foreach($privacies ?? [] as $rel)
<option value="{{ $rel->id }}">{{ $rel->name }}</option>
@endforeach
</select>
  </div>



<div class="form-group d-flex flex-column">
<div class="form-control d-flex justify-center align-items-center " style="outline:none; border:none;">
<label style="padding-left:10px; margin-bottom:2px;" >تعطيل التتبع</label>
<input type='checkbox'  name="tracing">
</div>




</div>
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
<h4 class="mb-0">إدارة الجوابات</h4>
<div class="bulkActions">
<button class="btn btn-primary add-btn" data-toggle="modal" data-target="#addModal"><i class="fa fa-plus"></i> إضافة</button>
<button class="btn btn-danger del-all-btn" id="bulkDelete"><i class="fa fa-trash"></i> حذف المحدد</button>
<button class="btn btn-warning text-white archive-all-btn" id="bulkArchive"><i class="fa fa-archive"></i> أرشفة المحدد</button>
</div>
</div>
<div class="card-body">
<input type="text" id="searchInput" class="form-control mb-3" placeholder="بحث سريع عن الجوابات...">
<div class="table-responsive">
<table class="table table-hover text-center" id="entityTable">
<thead class="thead-light">
<tr>
<th><input type='checkbox' id='selectAll' style="margin-top:10px;"></th>
<th>العنوان</th>
<th>الوصف</th>
<th>حاله النشر</th>
<th>الجهه المصدره</th>
<th>التوقيع</th>
<th>العمليات</th>
</tr>
</thead>

<tbody id="index">
@forelse ($mails as $item)
<tr id="row-{{ $item->id }}">
<td><input type='checkbox' class="selectItem" name="ids[]" form="selectedGroub" value='{{ $item->id }}'></td>
<td>{{ $item->title }}</td>
<td>{{ $item->description }}</td>
<td>{{ $item->status->name ?? 'N/A' }}</td>
<td>{{ $item->entity->name ?? 'N/A' }}</td>
<td>{{ $item->sign ?? 'غير موقع' }}</td>
<td>
<button class="btn btn-sm btn-purple shareBtn"
data-toggle="modal"
data-target="#shareModal"
data-id="{{ $item->id }}">
<i class="fa fa-share"></i>
</button>
<a class="btn btn-sm btn-secondary signBtn" href="{{ route('mail.sign', $item->id) }}"><i class="fa fa-signature"></i></a>
<a class="btn btn-sm btn-success viewBtn" href="{{ route('mail.preview', $item->id) }}"><i class="fa fa-eye"></i></a>
<button class="btn btn-sm btn-info editBtn"
data-toggle="modal"
data-target="#editModal"
data-id="{{ $item->id }}"
data-edit_title="{{ $item->title }}"
data-edit_description="{{ $item->description }}"
data-edit_entity_id="{{ $item->entity->id }}">
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
    <div class="pag-div">
      {{ $mails->links('pagination::bootstrap-5') }}
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
$('.editBtn').on('click',function(){
let edit_title=  $(this).data('edit_title');
let edit_description =  $(this).data('edit_description');
let edit_department_id =  $(this).data('edit_department_id');
let edit_entity_id =  $(this).data('edit_entity_id');
let id =  $(this).data('id');
$('#editModal').on('shown.bs.modal', function () {
$('#edit_title').val(edit_title);
$('#edit_description').val(edit_description);
$('#edit_department_id').val(edit_department_id);
$('#edit_entity_id').val(edit_entity_id);
$('#edit_id').val(id);
});
})
</script>

<script>
$('.shareBtn').on('click',function(){
let id =  $(this).data('id');
$('#shareModal').on('shown.bs.modal', function () {
$('#share_id').val(id);
});
})
</script>



<script>
document.querySelectorAll('.deleteBtn').forEach(function(btn) {
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
window.location.href = "/mail/destroy/"+id;
}
});
});
});
</script>

<script>
document.querySelectorAll('.archiveBtn').forEach(function(btn) {
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
window.location.href = "/mail/archive/"+id;
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
form.action = `{{ route('mail.destroy.all') }}`;
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
form.action = `{{ route('mail.archive.all') }}`;
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
url: '/mail/search',
type: 'post',
data: { search: search },
success: function(data) {

console.log(data);
let html = '';
if (!data || data.length === 0) {
html = `<tr><td colspan="20"><p class="no-data">لا توجد نتائج للبحث</p></td></tr>`;
} else {
$.each(data, function(i, item) {
let signUrl = '/mail/sign/'+item.id;
let previewUrl = '/mail/preview/'+item.id;
html += `
<tr id="row-${item.id}">
<td><input type='checkbox' class="selectItem" name="ids[]" value='${item.id}'></td>
<td>${item.title}</td>
<td>${item.description}</td>
<td>${item.status?.name ?? 'N/A'}</td>
<td>${item.entity?.name ?? 'N/A'}</td>
<td>${item.sign ?? 'غير موقع'}</td>
<td>
<button class="btn btn-sm btn-purple shareBtn"
data-toggle="modal"
data-target="#shareModal"
data-id="${item.id}">
<i class="fa fa-share"></i>
</button>
<a class="btn btn-sm btn-secondary signBtn" href="${signUrl}"><i class="fa fa-signature"></i></a>
<a class="btn btn-sm btn-success viewBtn" href="${previewUrl}"><i class="fa fa-eye"></i></a>
    <button class="btn btn-sm btn-info editBtn"
            data-toggle="modal"
            data-target="#editModal"
            data-id="${item.id}"
            data-edit_title="${item.title}"
            data-edit_description="${item.description}"
            data-edit_entity_id="${item.entity.id}">
        <i class="fa fa-edit"></i>
    </button>
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
