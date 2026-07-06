import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});




window.startListening = (userId) => {
    window.Echo.private(`App.Models.User.${userId}`)
        .notification((notification) => {
            console.log('وصل إشعار جديد:', notification);

            // استدعاء دالة الـ Toast بدلاً من الـ Alert الرديء
            if (typeof window.showToast === 'function') {
                window.showToast(notification);
            } else {
                console.log('دالة showToast غير معرفة، محتوى الإشعار:', notification.message);
            }

            // 2. تحديث عداد الإشعارات في الهيدر
            const countElement = document.getElementById('notification-count');
            if (countElement) {
                let currentCount = parseInt(countElement.innerText);
                countElement.innerText = currentCount + 1;
            }

            // 3. إظهار النقطة النابضة (Pulse)
            const pulse = document.getElementById('notification-pulse');
            if (!pulse) {
                // إذا لم تكن موجودة، قم بإضافتها برمجياً
                const bellLink = document.querySelector('.main-header-notification .new.nav-link');
                const span = document.createElement('span');
                span.className = 'pulse';
                span.id = 'notification-pulse';
                bellLink.appendChild(span);
            }

            // 4. إضافة الإشعار الجديد إلى أول القائمة
            const list = document.getElementById('notifications-list');
            if (list) {
                // إزالة رسالة "لا توجد إشعارات" إذا كانت موجودة
                if (list.innerText.includes('لا توجد إشعارات')) {
                    list.innerHTML = '';
                }

                const newNotify = `
                    <a class="d-flex p-3 border-bottom bg-light" href="#">
                        <div class="notifyimg bg-warning">
                            <i class="la la-envelope-open text-white"></i>
                        </div>
                        <div class="mr-3">
                            <h5 class="notification-label mb-1">${notification.message}</h5>
                            <div class="notification-subtext">الآن</div>
                        </div>
                        <div class="mr-auto">
                            <i class="las la-angle-left text-left text-muted"></i>
                        </div>
                    </a>
                `;
                list.insertAdjacentHTML('afterbegin', newNotify);
            }
        });
};