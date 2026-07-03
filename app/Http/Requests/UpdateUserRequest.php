<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {    

        return [    
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email',
           #'password' => ['required', Rules\Password::defaults()],
            'role_id' => 'required',
            'department_id' => 'required',
            'user_status_id' => 'required',
            'id' => 'required',
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
       #'password.required' => 'يجب تعبئه حقل كلمه المرور',
        'role_id.required' => 'يجب تحديد دور المستخدم',
        'department_id.required' => 'يجب تحديد الاداره',
        'user_status_id.required' => 'يجب تحديد حاله للمستخدم',
        'id.required' => 'الطلب يحمل مشاكل امنيه' 
        ];
    }
}
