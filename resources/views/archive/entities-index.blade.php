@extends('layouts.master')
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الجهات</h4>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->

@endsection
@section('content')
<form action="" method="post" id="selectedGroub">
	@csrf
</form>
<div id="toastContainer"></div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">إدارة الجهات</h4>
                <div class="bulkActions">
                    <button class="btn btn-danger del-all-btn" id="bulkDelete"><i class="fa fa-trash"></i> حذف المحدد</button>
                    <button class="btn btn-warning restore-all-btn" id="bulkDelete"><i class="fa fa-rotate-left"></i>  أرجاع المحدد</button>
                </div>
            </div>
            <div class="card-body">
                <input type="text" id="searchInput" class="form-control mb-3" placeholder="بحث سريع عن الجهات...">
                <div class="table-responsive">
                    <table class="table table-hover text-center" id="entityTable">
                        <thead class="thead-light"><tr><th><input type='checkbox' id='selectAll' style="margin-top:10px;"></th><th>الاسم</th><th>العمليات</th></tr></thead>
                        <tbody id="index">
                            @forelse ($entities as $item)
                                <tr id="row-{{ $item->id }}">

                                    <td><input type='checkbox' class="selectItem" name="ids[]" form="selectedGroub" value='{{ $item->id }}'></td><td>{{ $item->name }}</td>
                                   <td>
                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $item->id }}"><i class="fa fa-trash"></i></button>
                                        <a href="{{route('entity.restore', $item->id)}}" class="btn btn-sm btn-warning restorBtn"><i class="fa fa-rotate-left"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="20"><p class="no-data">لا توجد بيانات للعرض</p></td></tr>
                            @endforelse
                        </tbody>
                    <tbody id="result">
					</tbody>
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
        }, 500); // نصف ثانية delay قبل ما يظهر
    });
</script>
@endif




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
			 form.action = `{{ route('entity.destroy.all') }}`;
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
			 form.action = `{{ route('entity.restore.all') }}`;
			 form.submit();
        }
        });
      }
    });
  });
</script>
<script>
$(document).ready(function() {
    // setup CSRF token
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
            return; // اخرج من الفانكشن عشان ميكملش للـ Ajax
        }

        $('#result').show();
        $('#index').hide();

        $.ajax({
            url: '/entity/search/archive',
            type: 'post',
            data: { search: search },
            success: function(data) {
                let html = '';
                if (!data || data.length === 0) {
                    html = `<tr><td colspan="20"><p class="no-data">لا توجد نتائج للبحث</p></td></tr>`;
                } else {
                    $.each(data, function(i, item) {
                       let restoreUrl = '/entity/restore/'+item.id;
                        html += `
                            <tr id="row-${item.id}">
                                <td><input type='checkbox' class="selectItem" name="ids[]" value='${item.id}'></td>
                                <td>${item.name}</td>
                                    <td>
                                    <button class="btn btn-sm btn-danger deleteBtn" data-id="${item.id}"><i class="fa fa-trash"></i></button>
                                    <a href="${restoreUrl}" class="btn btn-sm btn-warning restorBtn"><i class="fa fa-rotate-left"></i></a>
                                    </td>
                            </tr>`;
                    });
                }
                $('#result').html(html);
            },
            error: function(err) {
                console.error("خطأ في جلب البيانات:", err);
            }
        });
    });
});
</script>
<script>
$(document).on('click', '.editBtn', function() {
    let name = $(this).data('name');
    let id = $(this).data('id');

    $('#edit_name').val(name);
    $('#edit_id').val(id);
    $('#editModal').modal('show');
});
</script>

<script>
$(document).on('click', '.deleteBtn', function(e) {
    e.preventDefault();
    let id = $(this).data('id'); // جلب الـ id من الزر الذي ضُغط فعلياً

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
            window.location.href = "/entity/destroy/" + id;
        }
    });
});
</script>


<script>
$(document).on('click', '.archiveBtn', function(e) {
    e.preventDefault();
    let id = $(this).data('id');

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
            window.location.href = "/entity/archive/" + id;
        }
    });
});
</script>
@endsection
