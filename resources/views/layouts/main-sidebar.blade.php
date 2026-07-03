<!-- main-sidebar -->
		<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
		<aside class="app-sidebar sidebar-scroll">
			<div class="main-sidebar-header active">
				<a class="desktop-logo logo-light active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/logo.png')}}" class="main-logo" alt="logo"></a>
				<a class="desktop-logo logo-dark active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/logo-white.png')}}" class="main-logo dark-theme" alt="logo"></a>
				<a class="logo-icon mobile-logo icon-light active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/favicon.png')}}" class="logo-icon" alt="logo"></a>
				<a class="logo-icon mobile-logo icon-dark active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/favicon-white.png')}}" class="logo-icon dark-theme" alt="logo"></a>
			</div>
			<div class="main-sidemenu">
				<div class="app-sidebar__user clearfix">
					<div class="dropdown user-pro-body">
						<div class="">
							<img alt="user-img" class="avatar avatar-xl brround" src="{{URL::asset('assets/img/faces/6.jpg')}}"><span class="avatar-status profile-status bg-green"></span>
						</div>
						<div class="user-info">
							<h4 class="font-weight-semibold mt-3 mb-0">{{ Auth::user()->name }}</h4>
							<span class="mb-0 text-muted">{{ Auth::user()->role->name }}</span>
						</div>
					</div>
				</div>
				<ul class="side-menu">
					<li class="side-item side-item-category">الرئيسيه</li>					
			  		<li class="slide">
						<a class="side-menu__item" href="{{ route('dashboard') }}">
							<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14 9q-.425 0-.712-.288T13 8V4q0-.425.288-.712T14 3h6q.425 0 .713.288T21 4v4q0 .425-.288.713T20 9zM4 13q-.425 0-.712-.288T3 12V4q0-.425.288-.712T4 3h6q.425 0 .713.288T11 4v8q0 .425-.288.713T10 13zm10 8q-.425 0-.712-.288T13 20v-8q0-.425.288-.712T14 11h6q.425 0 .713.288T21 12v8q0 .425-.288.713T20 21zM4 21q-.425 0-.712-.288T3 20v-4q0-.425.288-.712T4 15h6q.425 0 .713.288T11 16v4q0 .425-.288.713T10 21z"/></svg>
							<span class="side-menu__label">لوحه التحكم</span></a>
					</li>
					@can('view-entities')							
					<li class="slide">
					<a class="side-menu__item" href="{{ route('entity.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m21.49 7.13l-9-5a.99.99 0 0 0-.97 0l-9.01 5C2.19 7.31 2 7.64 2 8v2c0 .55.45 1 1 1h2v7H3c-.55 0-1 .45-1 1v2c0 .55.45 1 1 1h18c.55 0 1-.45 1-1v-2c0-.55-.45-1-1-1h-2v-7h2c.55 0 1-.45 1-1V8a1 1 0 0 0-.51-.87M9 18H7v-7h2zm4 0h-2v-7h2zm-1-9c-.83 0-1.5-.67-1.5-1.5S11.17 6 12 6s1.5.67 1.5 1.5S12.83 9 12 9m5 9h-2v-7h2z"/></svg>					
						<span class="side-menu__label">الجهات</span></a>
					</li>
					@endcan

					@can('view-departments')							
				         <li class="slide">
						<a class="side-menu__item" href="{{ route('department.index') }}">
                          <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M18 15h-2v2h2m0-6h-2v2h2m2 6h-8v-2h2v-2h-2v-2h2v-2h-2V9h8M10 7H8V5h2m0 6H8V9h2m0 6H8v-2h2m0 6H8v-2h2M6 7H4V5h2m0 6H4V9h2m0 6H4v-2h2m0 6H4v-2h2m6-10V3H2v18h20V7z"/></svg>						
						    <span class="side-menu__label">الادارات</span></a>
					</li>
					@endcan	 
					<li class="slide">
						<a class="side-menu__item" href="{{ route('mail.index') }}">
                             <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 20q-.825 0-1.412-.587T2 18V6q0-.825.588-1.412T4 4h16q.825 0 1.413.588T22 6v12q0 .825-.587 1.413T20 20zm8.263-7.212q.137-.038.262-.113L19.6 8.25q.2-.125.3-.312t.1-.413q0-.5-.425-.75T18.7 6.8L12 11L5.3 6.8q-.45-.275-.875-.012T4 7.525q0 .25.1.438t.3.287l7.075 4.425q.125.075.263.113t.262.037t.263-.037"/></svg>							
							 <span class="side-menu__label">الجوابات</span></a>
					</li>
							<li class="slide">
						<a class="side-menu__item" href="{{ route('sign.index') }}">
                              <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M9.75 20.85c1.78-.7 1.39-2.63.49-3.85c-.89-1.25-2.12-2.11-3.36-2.94A9.8 9.8 0 0 1 4.54 12c-.28-.33-.85-.94-.27-1.06c.59-.12 1.61.46 2.13.68c.91.38 1.81.82 2.65 1.34l1.01-1.7C8.5 10.23 6.5 9.32 4.64 9.05c-1.06-.16-2.18.06-2.54 1.21c-.32.99.19 1.99.77 2.77c1.37 1.83 3.5 2.71 5.09 4.29c.34.33.75.72.95 1.18c.21.44.16.47-.31.47c-1.24 0-2.79-.97-3.8-1.61l-1.01 1.7c1.53.94 4.09 2.41 5.96 1.79m11.09-15.6c.22-.22.22-.58 0-.79l-1.3-1.3a.56.56 0 0 0-.78 0l-1.02 1.02l2.08 2.08M11 10.92V13h2.08l6.15-6.15l-2.08-2.08z"/></svg>						
							  	<span class="side-menu__label">التوقيعات</span></a>
					</li>


					@can('view-users')							
						<li class="slide">
						<a class="side-menu__item" href="{{ route('user.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon"  width="256" height="256" viewBox="0 0 256 256"><path fill="currentColor" d="M64.12 147.8a4 4 0 0 1-4 4.2H16a8 8 0 0 1-7.8-6.17a8.35 8.35 0 0 1 1.62-6.93A67.8 67.8 0 0 1 37 117.51a40 40 0 1 1 66.46-35.8a3.94 3.94 0 0 1-2.27 4.18A64.08 64.08 0 0 0 64 144c0 1.28 0 2.54.12 3.8m182-8.91A67.76 67.76 0 0 0 219 117.51a40 40 0 1 0-66.46-35.8a3.94 3.94 0 0 0 2.27 4.18A64.08 64.08 0 0 1 192 144c0 1.28 0 2.54-.12 3.8a4 4 0 0 0 4 4.2H240a8 8 0 0 0 7.8-6.17a8.33 8.33 0 0 0-1.63-6.94Zm-89 43.18a48 48 0 1 0-58.37 0A72.13 72.13 0 0 0 65.07 212A8 8 0 0 0 72 224h112a8 8 0 0 0 6.93-12a72.15 72.15 0 0 0-33.74-29.93Z"/></svg>						
							<span class="side-menu__label">المستخدمين</span></a>
					</li>
					@endcan
					</ul>
			</div>
		</aside>
<!-- main-sidebar -->
