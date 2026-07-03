/*------------------------------------------------------------------
[Select2 Global Initialization Script]

Project        :   Dashboard
File Name      :   global-select2.js
Version        :   1.0
Create Date    :   29/10/2025
Author         :   Mazen Mohamed
Description    :   Initialize all Select2 elements with Arabic language and default placeholders.
-------------------------------------------------------------------*/

$('.mySelect').each(function () {
    const $select = $(this);
    const $modal = $select.closest('.modal'); // يجيب أقرب مودال للسيليكت ده

    $select.select2({
        language: {
            noResults: () => "لا توجد نتائج",
            inputTooShort: () => "اكتب كلمة للبحث",
            searching: () => "جاري البحث..."
        },
        placeholder: "بحث",
        dropdownParent: $modal.length ? $modal : $('body'),
        width: '100%'
    });

    if ($select.find('option').length === 0) {
        $select.append('<option disabled selected>لا تتوفر بيانات</option>');
    }
});

$('.mySelectMultiple').each(function () {
    const $select = $(this);
    const $modal = $select.closest('.modal');

    $select.select2({
        language: {
            noResults: () => "لا توجد نتائج",
        },
        placeholder: "اختار",
        dropdownParent: $modal.length ? $modal : $('body'),
        width: '100%',
        minimumResultsForSearch: Infinity, 

    });

});
