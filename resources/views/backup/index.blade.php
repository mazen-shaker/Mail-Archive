@extends('layouts.master')
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">النسخ الاحتياطية</h4>
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

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editForm" action="{{route('backup.update')}}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-content">
                <div class="modal-header"><h5>تعديل النسخة الاحتياطية</h5></div>
                <div class="modal-body">
                <div class="form-group">
                    <label>الاسم</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
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
                <h4 class="mb-0">إدارة النسخ الاحتياطية</h4>
                <div class="bulkActions">
                    <button class="btn btn-danger del-all-btn" id="bulkDelete"><i class="fa fa-trash"></i> حذف المحدد</button>
                    <button class="btn btn-warning text-white save-all-btn" id="bulkSave"><i class="fa fa-bookmark"></i> حفظ المحدد</button>
                    <button class="btn btn-warning text-white desave-all-btn" id="bulkDesave">أزاله المحدد من الحفظ</button>
                </div>
            </div>
            <div class="card-body">
                <input type="text" id="searchInput" class="form-control mb-3" placeholder="بحث سريع عن النسخ الاحتياطية...">
                <div class="table-responsive">
                    <table class="table table-hover text-center" id="backupTable">
                        <thead class="thead-light"><tr><th><input type='checkbox' id='selectAll' style="margin-top:10px;"></th><th>الاسم</th><th>العمليات</th></tr></thead>
                        <tbody id="index">
                            @forelse ($backups as $item)
                                <tr id="row-{{ $item->id }}">
                                    <td><input type='checkbox' class="selectItem" name="ids[]" form="selectedGroub" value='{{ $item->id }}'></td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info editBtn" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}" data-name="{{ $item->name }}"><i class="fa fa-edit"></i></button>
                                        @if(!$item->deleted_at)
                                        <a class="btn btn-sm btn-warning text-white saveBtn" href="{{route("backup.save", $item->id)}}"><i class="fa fa-bookmark"></i></a>
                                        @else
                                        <a class="btn btn-sm btn-warning text-white desaveBtn" href="{{route("backup.desave", $item->id)}}">ازاله من الحفظ</a>
                                        @endif
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
    <div class="pag-div">
      {{ $backups->links('pagination::bootstrap-5') }}
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
          title: 'اختار نسخة احتياطية الأول',
          confirmButtonText: 'حسنا'
        });
        return;
      }

      // لو فيه عناصر متعلمه
      if (btn.classList.contains('del-all-btn')) {
        Swal.fire({
          title: 'هل متأكد من عمليه الحذف؟',
          text: "سوف يتم حذف النسخ الاحتياطية نهائياً",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'نعم',
          cancelButtonText: 'رجوع'
        }).then((result) => {
          if (result.isConfirmed) {
             let form = document.getElementById('selectedGroub');
			 form.action = `{{ route('backup.destroy.all') }}`;
			 form.submit();
        }
        });
      }else if (btn.classList.contains('save-all-btn')) {
             let form = document.getElementById('selectedGroub');
			 form.action = `{{ route('backup.save.all') }}`;
			 form.submit();
      }else if (btn.classList.contains('desave-all-btn')) {
             let form = document.getElementById('selectedGroub');
			 form.action = `{{ route('backup.desave.all') }}`;
			 form.submit();
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
            url: '/backup/search',
            type: 'post',
            data: { search: search },
            success: function(data) {
                let html = '';
                if (!data || data.length === 0) {
                    html = `<tr><td colspan="20"><p class="no-data">لا توجد نتائج للبحث</p></td></tr>`;
                } else {
                    $.each(data, function(i, item) {
                    let saveUrl = '/backup/save/'+item.id;
                    let saveUrl = '/backup/desave/'+item.id;
                        html += `
                            <tr id="row-${item.id}">
                                <td><input type='checkbox' class="selectItem" name="ids[]" value='${item.id}'></td>
                                <td>${item.name}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info editBtn" data-toggle="modal" data-target="#editModal" data-id="${item.id}" data-name="${item.name}"><i class="fa fa-edit"></i></button>
                                        ${item.deleted_at === null ? `<a class="btn btn-sm btn-warning text-white saveBtn" href="${saveUrl}"><i class="fa fa-bookmark"></i></a>` : `<a class="btn btn-sm btn-warning text-white desaveBtn" href="${desaveUrl}">أزاله الحفظ</a>`}
                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="${item.id}"><i class="fa fa-trash"></i></button>
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
        text: "سوف يتم حذف النسخة الاحتياطية نهائياً",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم',
        cancelButtonText: 'رجوع'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "/backup/destroy/" + id;
        }
    });
});
</script>
@endsection
