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


<div class="row">
<div class="col-12">
<div class="card">
<div class="card-header bg-white d-flex justify-content-between align-items-center">
<h4 class="mb-0">إدارة الجوابات</h4>
<div class="bulkActions">
<button class="btn btn-danger del-all-btn" id="bulkDelete"><i class="fa fa-trash"></i> حذف المحدد</button>
<button class="btn btn-warning restore-all-btn" id="bulkDelete"><i class="fa fa-rotate-left"></i>  أرجاع المحدد</button>
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
<a class="btn btn-sm btn-success viewBtn" href="{{ route('mail.preview', $item->id) }}"><i class="fa fa-eye"></i></a>
<button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $item->id }}"><i class="fa fa-trash"></i></button>
<a href="{{route('mail.restore', $item->id)}}" class="btn btn-sm btn-warning restorBtn"><i class="fa fa-rotate-left"></i></a>
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
     } else if(btn.classList.contains('restore-all-btn')) {
        Swal.fire({
          title: 'هل متأكد من عمليه الارجاع',
          text: "سوف يتم ارجاع العنصر",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'نعم',
          cancelButtonText: 'رجوع'
        }).then((result) => {
          if (result.isConfirmed) {
             let form = document.getElementById('selectedGroub');
			 form.action = `{{ route('mail.restore.all') }}`;
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
url: '/mail/search/archive',
type: 'post',
data: { search: search },
success: function(data) {

console.log(data);
let html = '';
if (!data || data.length === 0) {
html = `<tr><td colspan="20"><p class="no-data">لا توجد نتائج للبحث</p></td></tr>`;
} else {
$.each(data, function(i, item) {
let restoreUrl = '/mail/restore/'+item.id;
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
<a class="btn btn-sm btn-success viewBtn" href="${previewUrl}"><i class="fa fa-eye"></i></a>
<button class="btn btn-sm btn-danger deleteBtn" data-id="${item.id}"><i class="fa fa-trash"></i></button>
<a href="${restoreUrl}" class="btn btn-sm btn-warning restorBtn"><i class="fa fa-rotate-left"></i></a>
</td>
</tr>`;
});
}
$('#result').html(html);
}
});
});
});
</script>@endsection
