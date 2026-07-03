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
        'name.required' => 'يجب ملأ حقل الاسم ',  
        'name.string' => 'الاسم جب ان يكون نص',     
        'name.max' => 'اقصى عدد حروف 255 للاسم',
        'email.string' => 'الوصف جب ان يكون نص',  
        'email.lowercase' => 'يجب ان تكون حروف البريد الالكتروني صغيره',
        'email.email' => 'يجب ان تكون صيغه البريد الالكتروني صحيحه',
        'email.unique' => 'يجب ان يكون البريد الالكتروني غير مكرر',
        'password.required' => 'يجب تعبئه حقل كلمه المرور',
        'role_id.required' => 'يجب تحديد دور المستخدم',
        'department_id.required' => 'يجب تحديد الاداره',
        'user_status_id.required' => 'يجب تحديد حاله للمستخدم',

        ];
    }
}
