<!-- Back-to-top -->
<a href="#top" id="back-to-top"><i class="las la-angle-double-up"></i></a>
<!-- JQuery min js -->
<script src="{{URL::asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap Bundle js -->
<script src="{{URL::asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Ionicons js -->
<script src="{{URL::asset('assets/plugins/ionicons/ionicons.js')}}"></script>
<!-- Moment js -->
<script src="{{URL::asset('assets/plugins/moment/moment.js')}}"></script>

<!-- Rating js-->
<script src="{{URL::asset('assets/plugins/rating/jquery.rating-stars.js')}}"></script>
<script src="{{URL::asset('assets/plugins/rating/jquery.barrating.js')}}"></script>

<!--Internal  Perfect-scrollbar js -->
<script src="{{URL::asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/perfect-scrollbar/p-scroll.js')}}"></script>
<!--Internal Sparkline js -->
<script src="{{URL::asset('assets/plugins/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
<!-- Custom Scroll bar Js-->
<script src="{{URL::asset('assets/plugins/mscrollbar/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<!-- right-sidebar js -->
<script src="{{URL::asset('assets/plugins/sidebar/sidebar-rtl.js')}}"></script>
<script src="{{URL::asset('assets/plugins/sidebar/sidebar-custom.js')}}"></script>
<!-- Eva-icons js -->
<script src="{{URL::asset('assets/js/eva-icons.min.js')}}"></script>
@yield('js')

<!-- Sticky js -->
<script src="{{URL::asset('assets/js/sticky.js')}}"></script>
<!-- custom js -->
<script src="{{URL::asset('assets/js/custom.js')}}"></script><!-- Left-menu js-->
<script src="{{URL::asset('assets/plugins/side-menu/sidemenu.js')}}"></script>
<!-- Internal Modal js-->
<script src="{{URL::asset('assets/js/modal.js')}}"></script>
<!-- Sweet alert2-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Internal Select2 js-->
<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<!--Internal  Datepicker js -->
<script src="{{URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
<!--select2 global customed script js -->
<script src="{{URL::asset('assets\js\global-select2.js')}}"></script>
<!--Internal Fileuploads js-->
<script src="{{URL::asset('assets/plugins/fileuploads/js/fileupload.js')}}"></script>
<script src="{{URL::asset('assets/plugins/fileuploads/js/file-upload.js')}}"></script>
<!--Filepond js-->
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<script src="{{URL::asset('assets/js/filepond.js')}}"></script>

<script>
    window.userId = {{ auth()->id() }};
</script>
<script>
window.showToast = function(notification) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        // إضافة أنيميشن للدخول والخروج
        showClass: {
            popup: 'animate__animated animate__fadeInRight'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutRight'
        }
    });

    Toast.fire({
        // استخدام أيقونة FontAwesome بدلاً من أيقونات المكتبة التقليدية
        html: `
            <div style="display: flex; align-items: center;">
                <div style="background: #eef2ff; color: #4f46e5; padding: 10px; border-radius: 50%; margin-left: 15px;">
                    <i class="fas fa-bell"></i> </div>
                <div style="text-align: right;">
                    <div style="font-weight: bold; color: #1f2937;">${notification.title || 'تنبيه جديد'}</div>
                    <div style="font-size: 0.85em; color: #6b7280;">${notification.message || ''}</div>
                </div>
            </div>
        `,
        background: '#ffffff',
        customClass: {
            popup: 'my-custom-toast'
        }
    });
}
</script>
<script>
// 1. حذف إشعار واحد (بدون تأكيد)
function deleteNotification(id) {
    fetch(`/notifications/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const element = document.getElementById(`notification-${id}`);
            if (element) {
                element.style.opacity = '0';
                element.style.transform = 'translateX(20px)';
                setTimeout(() => {
                    element.remove();
                    updateNotificationCount(-1);
                    checkEmptyList();
                }, 300);
            }
        }
    });
}

// 2. حذف جميع الإشعارات
function deleteAllNotifications() {
    fetch(`/notifications/delete-all`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const list = document.getElementById('notifications-list');
            list.style.opacity = '0'; // حركة اختفاء للقائمة كاملة
            setTimeout(() => {
                list.innerHTML = '<div class="p-3 text-center text-muted" id="no-notifications">لا توجد إشعارات حالياً</div>';
                list.style.opacity = '1';
                document.getElementById('notification-count').innerText = '0';
                // إخفاء النقطة النابضة إذا وجدت
                const pulse = document.getElementById('notification-pulse');
                if(pulse) pulse.remove();
            }, 300);
        }
    });
}

// دالة مساعدة لتحديث العداد
function updateNotificationCount(change) {
    const countElement = document.getElementById('notification-count');
    if (countElement) {
        let currentCount = parseInt(countElement.innerText);
        countElement.innerText = Math.max(0, currentCount + change);
    }
}

// دالة مساعدة للتأكد من حالة القائمة
function checkEmptyList() {
    const list = document.getElementById('notifications-list');
    if (list && list.querySelectorAll('.d-flex.p-3').length === 0) {
        list.innerHTML = '<div class="p-3 text-center text-muted" id="no-notifications">لا توجد إشعارات حالياً</div>';
    }
}
</script>

