<!-- Title -->
<title>warala</title>
<!-- Favicon -->
<link rel="icon" href="{{URL::asset('assets/img/brand/favicon.png')}}" type="image/x-icon"/>
<script src="https://kit.fontawesome.com/828895ed57.js" crossorigin="anonymous"></script>
<!--select2-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!--filepond-->
<link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet">
<!---Internal Fileupload css-->
<link href="{{URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" type="text/css"/>
<!---Internal Fancy uploader css-->
<link href="{{URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css')}}" rel="stylesheet" />
<!-- Icons css -->
<link href="{{URL::asset('assets/css/icons.css')}}" rel="stylesheet">
<!--  Custom Scroll bar-->
<link href="{{URL::asset('assets/plugins/mscrollbar/jquery.mCustomScrollbar.css')}}" rel="stylesheet"/>
<!--  Sidebar css -->
<link href="{{URL::asset('assets/plugins/sidebar/sidebar.css')}}" rel="stylesheet">
<!-- Sidemenu css -->
<link rel="stylesheet" href="{{URL::asset('assets/css-rtl/sidemenu.css')}}">
@yield('css')
<!--- Style css -->
<link href="{{URL::asset('assets/css-rtl/toast.css')}}" rel="stylesheet">

<link href="{{URL::asset('assets/css-rtl/style.css')}}" rel="stylesheet">

<!--- Dark-mode css -->
<link href="{{URL::asset('assets/css-rtl/style-dark.css')}}" rel="stylesheet">
<!---Skinmodes css-->
<link href="{{URL::asset('assets/css-rtl/skin-modes.css')}}" rel="stylesheet">
<!---custom css-->
<link href="{{URL::asset('assets/css-rtl/custom-data-pages-1.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/css-rtl/matrix-page.css')}}" rel="stylesheet">
<style>

.swal2-popup div:hover{
cursor:pointer !important;
}

.my-custom-toast {
    cursor: pointer;
    transition: opacity .35s ease, transform .35s ease;
}

.my-custom-toast:hover {
    background: #f3f4f6 !important; /* أغمق سنة */
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,.15);
    cursor: pointer;
}


.notf-notf{
  border-radius:20px !important;
  margin-top:10px !important;
  margin-bottom:5px !important;
  margin-right:6px;
  margin-left:6px;
}
.notf-notf:hover{
cursor:pointer !important;
background-color:#e0e0e0 !important;
transition: all 0.3s ease;
}

.notification-label:hover {
color:black;
}


.main-notification-list {
padding-bottom:10px !important;
border-bottom-left-radius: 12px !important;
border-bottom-right-radius: 12px !important;
}

.notf-derop {
border-bottom-left-radius: 12px !important;
border-bottom-right-radius: 12px !important;

}
</style>

