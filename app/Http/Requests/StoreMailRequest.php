<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


public function rules(): array
{
    return [
        'title' => 'required|string',
        'description' => 'required|string',
        'entity_id' => 'required',
        'file' => 'required|file|mimes:pdf|max:10240',
    ];
}

public function messages(): array
{
    return [
        'title.required' => 'يجب ملأ حقل العنوان',
        'title.string' => 'العنوان يجب أن يكون نص',

        'description.required' => 'يجب ملأ حقل الوصف',
        'description.string' => 'الوصف يجب أن يكون نص',

        'entity_id.required' => 'يجب تحديد الجهة المرسلة',

        'file.required' => 'يجب رفع الجواب',
        'file.file' => 'الملف المرفوع غير صالح',
        'file.mimes' => 'يجب أن يكون الملف بصيغة PDF',
        'file.max' => 'حجم الملف يجب ألا يتجاوز 10 ميجابايت',
    ];
}
}

