@extends('layouts.master')
@section('css')
    <!-- Sidemenu-responsive-tabs css -->
    <link href="{{ URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css') }}" rel="stylesheet">
    <style>
        .error-text {
            color: red;
            font-size: 12px;
            margin-bottom: 5px;
            display: block;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0px 1000px white inset;
            -webkit-text-fill-color: #000;
            box-shadow: 0 0 0px 1000px white inset;
            transition: background-color 5000s ease-in-out 0s;
        }

		#password{
			padding-left: 30px;
		}

		#passwordTwo{
			padding-left: 30px;
		}

		#passwordThree{
			padding-left: 30px;
		}

		#passwordFour{
			padding-left: 30px;
		}
    </style>
@endsection
@section('content')



<div class="sec-container">
	
    <div class="section">
      <h3 style="text-align: center;">معلومات الحساب</h3>
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

		<div class="form-group">
		<label for="name">الأسم</label>
		<input id="name" name="name"  class="form-control" value="{{Auth::user()->name}}" type="text">
		@error('name')
		<p class="error-text">{{$message}}</p>
		@enderror
		</div>
		<div class="form-group">
		<label for="email">البريد لاألكتروني</label>
		<input id="email" name="email" class="form-control" value="{{Auth::user()->email}}" type="email">
		@error('email')
		<p class="error-text">{{$message}}</p>
		@enderror
		</div>
		<div class="form-group" style="display:flex;">
		<button class="btn ripple btn-primary" type="submit" style="width:100%;">حفظ</button>
		</div>
		</form>
    </div>


    <div class="section">
      <h3 style="text-align: center;">تغير كلمه المرور</h3>
    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="form-group">
            <label>كلمة المرور الحاليه</label>
            <div style="position: relative;">
                <input class="form-control"
                       id="passwordThree"
                       type="password"
                       name="current_password"  
                       required
                       @if($errors->updatePassword->get('current_password')) style="border:solid 1px red; background-color:rgba(255, 210, 210, 0.21);" @endif>
                <i id="togglePasswordThree" class="fa-solid fa-eye"
                   style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); cursor: pointer;"></i>
            </div>
            @foreach ($errors->updatePassword->get('current_password') as $message)
                <span class="error-text">{{ $message }}</span>
            @endforeach
        </div>


        <div class="form-group">
            <label>كلمة المرور</label>
            <div style="position: relative;">
                <input class="form-control"
                       id="password"
                       type="password"
                       name="password"
                       required
                       @if($errors->updatePassword->get('password')) style="border:solid 1px red; background-color:rgba(255, 210, 210, 0.21);" @endif>
                <i id="togglePassword" class="fa-solid fa-eye"
                   style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); cursor: pointer;"></i>
            </div>
            @foreach ($errors->updatePassword->get('password') as $message)
                <span class="error-text">{{ $message }}</span>
            @endforeach
        </div>

        <div class="form-group">
            <label>تأكيد كلمة المرور</label>
            <div style="position: relative;">
                <input class="form-control"
                       id="passwordTwo"
                       type="password"
                       name="password_confirmation"
                       required
                       @if($errors->updatePassword->get('password_confirmation')) style="border:solid 1px red; background-color:rgba(255, 210, 210, 0.21);" @endif>
                <i id="togglePasswordTwo" class="fa-solid fa-eye"
                   style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); cursor: pointer;"></i>
            </div>
            @foreach ($errors->updatePassword->get('password_confirmation') as $message)
                <span class="error-text">{{ $message }}</span>
            @endforeach
        </div>


		<div class="form-group" style="display:flex;">
		<button class="btn ripple btn-primary" type="submit" style="width:100%;">تأكيد</button>
		</div>
		</form>
    </div>


	    <div class="section">
      <h3 style="text-align: center;">حذف الحساب</h3>
    <form method="post" action="{{ route('profile.destroy') }}" id="deleteForm" class="mt-6 space-y-6">
        @csrf
        @method('delete')

        <div class="form-group">
            <label>كلمة المرور</label>
            <div style="position: relative;">
                <input class="form-control"
                       id="passwordFour"
                       type="password"
                       name="password"
                       required
                       @if($errors->userDeletion->get('password')) style="border:solid 1px red; background-color:rgba(255, 210, 210, 0.21);" @endif>
                <i id="togglePasswordFour" class="fa-solid fa-eye"
                   style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); cursor: pointer;"></i>
            </div>
            @foreach ($errors->userDeletion->get('password') as $message)
                <span class="error-text">{{ $message }}</span>
            @endforeach
        </div>

		<div class="form-group" style="display:flex;">
		<button class="btn ripple btn-danger del-btn" type="submit" style="width:100%;">حذف</button>
		</div>
		</form>
    </div>
  </div>
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection


@section('js')


<script>
    const password = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    const passwordTwo = document.getElementById('passwordTwo');
    const togglePasswordTwo = document.getElementById('togglePasswordTwo');

    togglePasswordTwo.addEventListener('click', function () {
        const type = passwordTwo.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordTwo.setAttribute('type', type);

        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    const passwordThree = document.getElementById('passwordThree');
    const togglePasswordThree = document.getElementById('togglePasswordThree');

    togglePasswordThree.addEventListener('click', function () {
        const type = passwordThree.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordThree.setAttribute('type', type);

        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    const passwordFour = document.getElementById('passwordFour');
    const togglePasswordFour = document.getElementById('togglePasswordFour');

    togglePasswordFour.addEventListener('click', function () {
        const type = passwordFour.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordFour.setAttribute('type', type);

        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>

<script>
  document.querySelectorAll('.del-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault(); 
      Swal.fire({
        title: 'هل متأكد من عمليه حذف الحساب؟',
        text: "سوف يتم حذف الحساب نهائياً",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم',
        cancelButtonText: 'رجوع'
      }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    });
  });
</script>
@if(session('status'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    icon: 'success',
    title: "{{ session('status') }}",
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});
</script>
@endif
@endsection