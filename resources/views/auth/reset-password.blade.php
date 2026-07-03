@extends('layouts.master2')

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
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row no-gutter">
        <!-- Content half -->
        <div class="col-md-6 col-lg-6 col-xl-5 bg-white">
            <div class="login d-flex align-items-center py-2">
                <div class="container p-0">
                    <div class="row">
                        <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                            <div class="mb-5 d-flex">
                                <a href="{{ url('/' . $page = 'index') }}">
                                    <img src="{{ URL::asset('assets/img/brand/favicon.png') }}" class="sign-favicon ht-40" alt="logo">
                                </a>
                                <h1 class="main-logo1 ml-1 mr-0 my-auto tx-28">war<span>al</span>a</h1>
                            </div>
                            <div class="main-card-signin d-md-flex">
                                <div class="wd-100p">
                                    <div class="main-signin-header">
                                        <h2>مرحبا بك!</h2>
                                        <h4 class="text-right">إعادة تعيين كلمة مرور</h4>

                                        <form method="POST" action="{{ route('password.store') }}">
                                            @csrf
                                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                            <div class="form-group">
                                                <label>البريد الإلكتروني</label>
                                                <input class="form-control"
                                                       @error('email') style="border:solid 1px red; background-color:rgba(255, 210, 210, 0.21);" @enderror
                                                       placeholder="أدخل البريد الإلكتروني"
                                                       id="email"
                                                       type="email"
                                                       name="email"
                                                       required
                                                       autofocus
                                                       autocomplete="username">
                                                @error('email')
                                                    <span class="error-text">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>كلمة المرور</label>
                                                <div style="position: relative;">
                                                    <input class="form-control"
                                                           id="password"
                                                           type="password"
                                                           name="password"
                                                           required
                                                           autocomplete="new-password"
                                                           @error('password') style="border:solid 1px red; background-color:rgba(255, 210, 210, 0.21);" @enderror>
                                                    <i id="togglePassword" class="fa-solid fa-eye"
                                                       style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); cursor: pointer;"></i>
                                                </div>
                                                @error('password')
                                                    <span class="error-text">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>تأكيد كلمة المرور</label>
                                                <div style="position: relative;">
                                                    <input class="form-control"
                                                           id="passwordTwo"
                                                           type="password"
                                                           name="password_confirmation"
                                                           required
                                                           autocomplete="new-password"
                                                           @error('password_confirmation') style="border:solid 1px red; background-color:rgba(255, 210, 210, 0.21);" @enderror>
                                                    <i id="togglePasswordTwo" class="fa-solid fa-eye"
                                                       style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); cursor: pointer;"></i>
                                                </div>
                                                @error('password_confirmation')
                                                    <span class="error-text">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <button class="btn ripple btn-main-primary btn-block">إعادة التعيين</button>
                                        </form>
                                    </div>
                                    <div class="main-signup-footer mg-t-20">
                                        <p>تملك حساب؟<a href="{{ url('/' . $page = 'signin') }}"> تسجيل الدخول</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End -->
            </div>
        </div><!-- End -->

        <!-- Image half -->
        <div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent" style="padding:0;">
            <div class="row wd-100p mx-auto text-center">
                <img src="{{ URL::asset('assets/img/media/login.jpg') }}" style="margin:0;" alt="login-image">
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
</script>
@endsection
