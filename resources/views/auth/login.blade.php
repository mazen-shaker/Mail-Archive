@extends('layouts.master2')

@section('css')
    <!-- Sidemenu-responsive-tabs css -->
    <link href="{{ URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css') }}" rel="stylesheet">
    <style>
        .error-text {
            color: red;
            font-size: 12px;
            margin-bottom: -300px !important;
            margin-top: 200px !important;
            padding-right: 10px;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0px 1000px white inset; /* أو أي لون خلفية انت عايزه */
            -webkit-text-fill-color: #000; /* لون الخط */
            box-shadow: 0 0 0px 1000px white inset;
            transition: background-color 5000s ease-in-out 0s; /* يحافظ على اللون بدون فلاش */
        }

		#password{
			padding-left: 30px;
		}
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row no-gutter">
            <!-- The content half -->
            <div class="col-md-6 col-lg-6 col-xl-5 bg-white">
                <div class="login d-flex align-items-center py-2">
                    <div class="container p-0">
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                                <div class="card-sigin">
                                    <div class="mb-5 d-flex">
                                        <a href="{{ url('/' . $page = 'index') }}">
                                            <img src="{{ URL::asset('assets/img/brand/favicon.png') }}" class="sign-favicon ht-40" alt="logo">
                                        </a>
                                        <h1 class="main-logo1 ml-1 mr-0 my-auto tx-28">war<span>a</span>la</h1>
                                    </div>
                                    <div class="card-sigin">
                                        <div class="main-signup-header">
                                            <h2>أهلا بك!</h2>
                                            <h5 class="font-weight-semibold mb-4">من فضلك سجل الدخول للمتابعة</h5>

                                            <form method="POST" action="{{ route('login') }}">
                                                @csrf
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

                                                <button class="btn btn-main-primary btn-block">تسجيل الدخول</button>
                                            </form>

                                            <div class="main-signin-footer mt-5">
                                                <p><a href="{{ route('password.request') }}">نسيت كلمة المرور؟</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End -->
                </div>
            </div><!-- End -->

            <!-- The image half -->
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

            // تبديل الأيقونة
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
@endsection
