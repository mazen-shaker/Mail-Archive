@extends('layouts.master')
@section('page-header')
<div class="breadcrumb-header justify-content-between">
<div class="my-auto">
<div class="d-flex">
<h4 class="content-title mb-0 my-auto">التقارير</h4>
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
<h4 class="mb-0">التقارير</h4>
</div>
<div class="card-body">
<form action="{{route('mail.report')}}" method="get">
<div class="row">
<div class="col-md-3">
<div class="form-group"><label>من</label><input type="date" value="{{$resultFromDate ?? ''}}" name="date_from" class="form-control"></div>
</div>
<div class="col-md-3">
<div class="form-group"><label>إلى</label><input type="date" value="{{$resultToDate ?? ''}} name="date_to" class="form-control"></div>
</div>
<div class="col-md-2">
<div class="form-group"><label>الإدارات</label>
<select name="department" class="form-control mySelect">
<option value="" selected>-- عرض كل الأدارات --</option>
@foreach($departments as $item)
<option value="{{ $item->id }}">
    {{ $item->name }}
</option>
@endforeach
</select>
</div>
</div>
<div class="col-md-2">
<div class="form-group"><label>الجهات المصدرة</label>
<select name="entity"  class="form-control mySelect">
<option value="" selected>-- عرض كل الجهات المصدره --</option>
@foreach($entities as $item)
<option value="{{ $item->id }}">
    {{ $item->name }}
</option>
@endforeach
</select>
</div>
</div>
<div class="col-md-2">
<div class="form-group"><label>حالة النشر</label>
<select name="status"  class="form-control">
<option value="" selected>-- عرض كل الحالات --</option>
@foreach($statuses as $item)
<option value="{{ $item->id }}">
    {{ $item->name }}
</option>
@endforeach
</select>
</div>
</div>
</div>
<button type="submit" class="btn btn-primary">استعلام</button>
</form>
</div>
</div>
</div>
</div>

<div class="row">
<div class="col-12">
<div class="card">
<div class="card-body">
<div class="row">
<div class="col-md-4">
<div class="card">
<div class="card-body text-center">
<h4 class="mb-0">{{ $resultCount ?? 0 }}</h4>
<p class="mb-0">إجمالي الناتج</p>
</div>
</div>
</div>
<div class="col-md-4">
<div class="card">
<div class="card-body text-center">
<h4 class="mb-0">{{ $sharedCount ?? 0 }}</h4>
<p class="mb-0">منشور</p>
</div>
</div>
</div>
<div class="col-md-4">
<div class="card">
<div class="card-body text-center">
<h4 class="mb-0">{{ $archivedCount ?? 0 }}</h4>
<p class="mb-0">تمت الأرشفة</p>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="row">
<div class="col-12">
<div class="card">
<div class="card-header bg-white d-flex justify-content-between align-items-center">
<div class="bulkActions">
<button class="btn btn-success export-btn" ><i class="fas fa-table"></i> تصدير </button>
</div>
</div>
<div class="card-body">
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
</tr>
</thead>

<tbody id="index">
@forelse ($mails as $item)
<tr id="row-{{ $item->id }}">
<td><input type='checkbox' class="selectItem" name="ids[]" form="selectedGroub" value='{{ $item->id }}'></td>
<td>{{ $item->title }}</td>
<td>{{ $item->description }}</td>
<td>{{ $item->status->name ?? 'غير محدد' }}</td>
<td>{{ $item->entity->name ?? 'غير محدد' }}</td>
<td>{{ $item->sign ?? 'غير موقع' }}</td>
</tr>
@empty
<tr><td colspan="5"><p class="no-data">لا توجد بيانات للعرض</p></td></tr>
@endforelse
</tbody>
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




@endsection




@section('js')

<script>
  // تحديد العناصر
  const checkAll = document.getElementById('selectAll');
  const itemCheckboxes = document.querySelectorAll('.selectItem');
  const allBtns = document.querySelectorAll('.bulkActions button');

  // التحكم في تحديد الكل
  checkAll.addEventListener('change', function() {
    itemCheckboxes.forEach(cb => cb.checked = this.checked);
  });

  // التعامل مع الأزرار
  allBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();

      // استثناء زرار الإضافة
      if (btn.classList.contains('add-btn')) {
        console.log('زرار الإضافة اشتغل');
        return;
      }

      // التأكد من وجود تشيكبوكس متعلم
      const anyChecked = Array.from(itemCheckboxes).some(cb => cb.checked);

      if (!anyChecked) {
        Swal.fire({
          icon: 'info',
          title: 'اختار عنصر الأول',
          confirmButtonText: 'حسنا'
        });
        return;
      }

      // لو فيه عناصر متعلمه
    if (btn.classList.contains('export-btn')) {
    const form = document.getElementById('selectedGroub');
    form.action = `{{ route('mail.export') }}`;
    form.submit();
    }
    });
  });
</script>

@endsection
