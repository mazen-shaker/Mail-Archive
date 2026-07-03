document.addEventListener('DOMContentLoaded', () => {
  FilePond.create(document.querySelector('.filepond'), {
    /* الخاصية الأهم لربطها بـ Laravel Form */
    storeAsFile: true, 
    allowMultiple: true,
    name: 'attachments[]', // يجب أن يطابق الاسم في الـ Controller

    /* الإعدادات الجمالية الخاصة بك */
    labelIdle: ' اسحب الملفات هنا أو أختارها من <span class="filepond--label-action"> جهازك</span> ',
    credits: false,
    labelFileProcessing: 'جاري الرفع...',
    labelFileProcessingComplete: 'تم الرفع',
    labelFileProcessingAborted: 'تم الإلغاء',
    labelFileRemove: 'حذف',
    labelTapToCancel: 'اضغط للإلغاء',
    labelTapToRetry: 'اضغط لإعادة المحاولة',
  });
});