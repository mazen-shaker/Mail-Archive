@extends('layouts.master')
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">التوقيعات</h4>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->

@endsection
@section('content')
<form action="" method="post" id="selectedGroub">
	@csrf
</form>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('user.add') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5>إضافة المستخدمين</h5></div>
                <div class="modal-body">
                <div class="form-group">
                    <label>الاسم</label>
                    <input type="text" name="name" id="add_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>البريد</label>
                    <input type="text" name="email" id="add_email" class="form-control" required>
                </div>
                <div class="form-group">
                 <label>كلمة المرور</label>
                 <div style="position: relative;">
                     <input class="form-control"
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            @error('email') style="border:solid 1px red; background-color:rgba(255, 210, 210, 0.21);" @enderror>
                     <i id="togglePassword" class="fa-solid fa-eye"
                        style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); cursor: pointer;"></i>
                 </div>
                 @error('email')
                     <span class="error-text">{{ $message }}</span>
                 @enderror
             </div>
                <div class="form-group">
                    <label>الصلاحية</label>
                    <select name="role_id" id="add_role_id" class="form-control" required>
                        @foreach($roles ?? [] as $rel)
                            <option value="{{ $rel->id }}">{{ $rel->name ?? $rel->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>الاداره</label>
                    <select name="department_id" id="add_department_id" class="form-control mySelect" required>
                        @foreach($departments ?? [] as $rel)
                            <option value="{{ $rel->id }}">{{ $rel->name ?? $rel->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="user_status_id" id="add_user_status_id" class="form-control" required>
                        @foreach($statuss ?? [] as $rel)
                            <option value="{{ $rel->id }}">{{ $rel->name ?? $rel->title }}</option>
                        @endforeach
                    </select>
                </div></div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">حفظ</button></div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editForm" action="{{route('user.update')}}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-content">
                <div class="modal-header"><h5>تعديل المستخدمين</h5></div>
                <div class="modal-body">
                <div class="form-group">
                    <label>الاسم</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>البريد</label>
                    <input type="text" name="email" id="edit_email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>الصلاحية</label>
                    <select name="role_id" id="edit_role_id" class="form-control" required>
                        @foreach($roles ?? [] as $rel)
                            <option value="{{ $rel->id }}">{{ $rel->name ?? $rel->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>الاداره</label>
                    <select name="department_id" id="edit_department_id" class="form-control mySelect" required>
                        @foreach($departments ?? [] as $rel)
                            <option value="{{ $rel->id }}">{{ $rel->name ?? $rel->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="user_status_id" id="edit_user_status_id" class="form-control" required>
                        @foreach($statuss ?? [] as $rel)
                            <option value="{{ $rel->id }}">{{ $rel->name ?? $rel->title }}</option>
                        @endforeach
                    </select>
                </div></div>
                <div class="modal-footer"><button type="submit" class="btn btn-success">تحديث</button></div>
            </div>
        </form>
    </div>
</div>




<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">إدارة المستخدمين</h4>
                <div class="bulkActions">
                    <button class="btn btn-primary add-btn" data-toggle="modal" data-target="#addModal"><i class="fa fa-plus"></i> إضافة</button>
                    <button class="btn btn-danger del-all-btn" id="bulkDelete"><i class="fa fa-trash"></i> حذف المحدد</button>
                    <button class="btn btn-warning text-white archive-all-btn" id="bulkArchive"><i class="fa fa-archive"></i> أرشفة المحدد</button>
                </div>
            </div>
            <div class="card-body">
                <input type="text" id="searchInput" class="form-control mb-3" placeholder="بحث سريع عن المستخدمين...">
                <div class="table-responsive">
                    <table class="table table-hover text-center" id="userTable">
                        <thead class="thead-light"><tr><th><input type='checkbox' id='selectAll' style="margin-top:10px;"></th><th>الاسم</th><th>البريد</th><th>الصلاحية</th><th>القسم</th><th>الحالة</th><th>العمليات</th></tr></thead>
                        <tbody id="index">
                            @forelse ($users as $item)
                                <tr id="row-{{ $item->id }}">

                                    <td><input type='checkbox' class="selectItem" name="ids[]" form="selectedGroub" value='{{ $item->id }}'></td><td>{{ $item->name }}</td><td>{{ $item->email }}</td><td>{{ $item->role->name ?? 'N/A' }}</td><td>{{ $item->department->name ?? 'N/A' }}</td><td>{{ $item->status->name ?? 'N/A' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info editBtn" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-email="{{ $item->email }}" data-phone="{{ $item->phone }}" data-role_id="{{ $item->role_id }}" data-department_id="{{ $item->department_id }}" data-user_status_id="{{ $item->user_status_id }}"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-sm btn-warning text-white archiveBtn" data-id="{{ $item->id }}"><i class="fa fa-archive"></i></button>
                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $item->id }}"><i class="fa fa-trash"></i></button>
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
    <script>
        const password = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // تبديل الأيقونة
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
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
$(document).on('click', '.editBtn', function() {
let name =  $(this).data('name');
let email =  $(this).data('email');
let phone =  $(this).data('phone');
let role_id =  $(this).data('role_id');
let department_id =  $(this).data('department_id');
let user_status_id =  $(this).data('user_status_id');
let id =  $(this).data('id');
$('#editModal').on('shown.bs.modal', function () {
$('#edit_name').val(name);
$('#edit_email').val(email);
$('#edit_phone').val(phone);
$('#edit_role_id').val(role_id).change();
$('#edit_department_id').val(department_id).change();
$('#edit_user_status_id').val(user_status_id).change();
$('#edit_id').val(id);

});
})
</script>

<script>
$(document).on('click', '.deleteBtn', function(e) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
	  let id = btn.dataset.id;
      console.log(id);
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
             window.location.href = "/user/destroy/"+id;
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
      console.log(id);
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
             window.location.href = "/user/archive/"+id;
            }
        });
    });
  });
</script>





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
			 form.action = `{{ route('user.destroy.all') }}`;
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
			 form.action = `{{ route('user.archive.all') }}`;
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
            url: '/user/search',
            type: 'post',
            data: { search: search },
            success: function(data) {
                let html = '';
                if (!data || data.length === 0) {
                    html = `<tr><td colspan="20"><p class="no-data">لا توجد نتائج للبحث</p></td></tr>`;
                } else {
                    $.each(data, function(i, item) {
                        // تصليح الأخطاء هنا: item بدلاً من tem و استخدام النقطة بدلاً من السهم
                        html += `
                            <tr id="row-${item.id}">
                                <td><input type='checkbox' class="selectItem" name="ids[]" value='${item.id}'></td>
                                <td>${item.name}</td>
                                <td>${item.email}</td>
                                <td>${item.role ? item.role.name : 'N/A'}</td>
                                <td>${item.department ? item.department.name : 'N/A'}</td>
                                <td>${item.status ? item.status.name : 'N/A'}</td>
                                <td>
                                    <button class="btn btn-sm btn-info editBtn"
                                        data-toggle="modal"
                                        data-target="#editModal"
                                        data-id="${item.id}"
                                        data-name="${item.name}"
                                        data-email="${item.email}"
                                        data-role_id="${item.role_id}"
                                        data-department_id="${item.department_id}"
                                        data-user_status_id="${item.user_status_id}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning text-white archiveBtn" data-id="${item.id}"><i class="fa fa-archive"></i></button>
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
@endsection
