<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreUserRequest extends FormRequest
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
        'name' => 'required|string|max:255',

        'email' => 'required|string|lowercase|email|max:255|unique:users',

        'password' => ['required', Rules\Password::defaults()],

        'role_id' => 'required',
        'department_id' => 'required',
        'user_status_id' => 'required',
    ];
}

public function messages(): array
{
    return [
        'name.required' => 'يجب ملأ حقل الاسم',
        'name.string' => 'الاسم يجب أن يكون نص',
        'name.max' => 'الحد الأقصى لعدد أحرف الاسم هو 255',

        'email.required' => 'يجب ملأ حقل البريد الإلكتروني',
        'email.string' => 'البريد الإلكتروني يجب أن يكون نصًا',
        'email.lowercase' => 'يجب أن تكون أحرف البريد الإلكتروني صغيرة',
        'email.email' => 'يجب إدخال بريد إلكتروني بصيغة صحيحة',
        'email.max' => 'الحد الأقصى لعدد أحرف البريد الإلكتروني هو 255',
        'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',

        'password.required' => 'يجب تعبئة حقل كلمة المرور',

        'role_id.required' => 'يجب تحديد دور المستخدم',
        'department_id.required' => 'يجب تحديد الإدارة',
        'user_status_id.required' => 'يجب تحديد حالة المستخدم',
    ];
}


}
