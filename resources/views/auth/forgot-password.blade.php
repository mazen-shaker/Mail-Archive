@extends('layouts.master2')
@section('css')
<!-- Sidemenu-respoansive-tabs css -->
<link href="{{URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css')}}" rel="stylesheet">
<style>
	.error-text{
		color:red;
		font-size:12px;
		margin-bottom:-300px !important;
		margin-top:200px !important;
		padding-right:10px;
	}
	.error-input{
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
</style>
@endsection
@section('content')
		<div class="container-fluid">
			<div class="row no-gutter">
				<!-- The content half -->
				<div class="col-md-6 col-lg-6 col-xl-5 bg-white">
					<div class="login d-flex align-items-center py-2">
						<!-- Demo content-->
						<div class="container p-0">
							<div class="row">
								<div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
									<div class="mb-5 d-flex"> <a href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/favicon.png')}}" class="sign-favicon ht-40" alt="logo"></a><h1 class="main-logo1 ml-1 mr-0 my-auto tx-28">war<span>al</span>a</h1></div>
									<div class="main-card-signin d-md-flex">
										<div class="wd-100p">
											<div class="main-signin-header">
												<div class="">
													<h2>مرحبا بك!</h2>
													<h4 class="text-right">أرسال البريد الألكتروني</h4>
													    <form method="POST" action="{{ route('password.email') }}">
                                                         @csrf 
														<div class="form-group text-right">
														<label>البريد الألكتروني</label> <input class="form-control" @error('email') style="border:solid 1px red; background-color:rgba(255, 210, 210, 0.21);" @enderror placeholder="أدخل البريد الألكتروني" id="email" type="email" name="email" required autofocus >
														@error('email')
                                                        <span class="error-text">{{$message}}</span>
                                                        @enderror
													    </div>
														<button class="btn ripple btn-main-primary btn-block">أرسال</button>
													</form>
												</div>
											</div>
											<div class="main-signup-footer mg-t-20">
												<p>أتملك حساب? <a href="{{route('login')}}">تسجيل الدخول</a></p>
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
							<img src="{{URL::asset('assets/img/media/login.jpg')}}" class="" style="margin:0;" alt="logo">
					</div>
				</div>
			</div>
		</div>
@endsection
@section('js')
@endsection