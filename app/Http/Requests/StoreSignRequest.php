<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

public function rules(): array
{
    return [
        'name' => 'required|string',
        'file' => 'required|file|mimes:png|max:2048',
    ];
}

public function messages(): array
{
    return [
        'name.required' => 'يجب ملأ حقل الاسم',
        'name.string' => 'الاسم يجب أن يكون نص',

        'file.required' => 'يجب رفع صورة التوقيع',
        'file.file' => 'الملف المرفوع غير صالح',
        'file.mimes' => 'يجب أن تكون صورة التوقيع بصيغة PNG',
        'file.max' => 'حجم صورة التوقيع يجب ألا يتجاوز 2 ميجابايت',
    ];
}

}
