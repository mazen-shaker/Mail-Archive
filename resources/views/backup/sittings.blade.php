@extends('layouts.master')
@section('css')
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">التحكم في النظام</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الأعدادات /النسخ الأحتياطي </span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- row -->
				<div class="row">

				</div>



<div class="sec-container">
    <!-- القسم الأول: معلومات المؤسسة -->
    <div class="section">
      <h3 style="padding-bottom:40px;">النسخ الأحطياتي</h3>
					<a href="{{route('backup.pack')}}" class="btn ripple btn-primary" type="button">عمل سنخة</a>
					<a href="{{route('backup.index')}}" class="btn ripple btn-primary" type="button">عرض النسخ الأحتياطيه</a>

    </div>

    <!-- القسم الثاني: الموقع والتوقيت -->
    <div class="section">
      <h3>الخيارات</h3>
      <div class="form-group">
        <label>النسخ الاحتياطي التلقائي</label><br>
        <input type="checkbox" class="switch" checked>
      </div>
    </div>

        <div class="section" >
					<button class="btn ripple btn-primary" style="width:100%;" type="button">حفظ</button>
    </div>


  </div>
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
@endsection
