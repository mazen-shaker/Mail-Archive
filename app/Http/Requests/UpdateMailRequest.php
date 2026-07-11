<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     */

public function rules(): array
{
    return [
        'title' => 'required|string',
        'description' => 'required|string',
        'entity_id' => 'required',
        'mail_privacy_id' => 'required',
        'id' => 'required',
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

        'mail_privacy_id.required' => 'يجب تحديد خصوصية الخطاب',

        'id.required' => 'الطلب يحمل مشاكل أمنية',
    ];
}

}
