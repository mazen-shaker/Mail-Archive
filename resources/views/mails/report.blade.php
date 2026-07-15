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
<select name="department" class="form-control">
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
<select name="entity"  class="form-control">
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
<div class="card-body">
<div class="table-responsive">
<table class="table table-hover text-center" id="entityTable">
<thead class="thead-light">
<tr>
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
</div>
</div>
</div>
</div>
</div>
</div>




@endsection

